<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizeNetService
{
    protected string $apiLoginId;
    protected string $transactionKey;
    protected string $mode;
    protected string $endpoint;

    public function __construct()
    {
        $this->apiLoginId = (string) config('services.authorizenet.api_login_id', env('AUTHORIZENET_API_LOGIN_ID', ''));
        $this->transactionKey = (string) config('services.authorizenet.transaction_key', env('AUTHORIZENET_TRANSACTION_KEY', ''));
        $this->mode = strtoupper((string) config('services.authorizenet.mode', env('AUTHORIZENET_MODE', 'PRODUCTION')));

        $this->endpoint = ($this->mode === 'SANDBOX')
            ? 'https://apitest.authorize.net/xml/v1/request.api'
            : 'https://api.authorize.net/xml/v1/request.api';
    }

    /**
     * Charge Credit Card via Authorize.Net AIM/JSON API
     *
     * @param array $params
     * @return array
     */
    public function chargeCreditCard(array $params): array
    {
        // Clean card number
        $cardNumber = preg_replace('/\D/', '', (string) ($params['card_number'] ?? ''));
        $cardCode = trim((string) ($params['card_code'] ?? $params['cvv'] ?? ''));
        $rawExpiry = trim((string) ($params['expiration_date'] ?? $params['card_expiry'] ?? ''));
        $formattedExpiry = $this->formatExpirationDate($rawExpiry);

        // Split customer name
        $fullName = trim((string) ($params['customer_name'] ?? ($params['customer']['name'] ?? 'Customer')));
        $nameParts = explode(' ', $fullName, 2);
        $firstName = $nameParts[0] ?: 'Valued';
        $lastName = $nameParts[1] ?? 'Customer';

        $amount = number_format((float) ($params['amount'] ?? 0), 2, '.', '');
        $orderNumber = (string) ($params['order_number'] ?? ('AVP-' . time()));

        $payload = [
            'createTransactionRequest' => [
                'merchantAuthentication' => [
                    'name' => $this->apiLoginId,
                    'transactionKey' => $this->transactionKey,
                ],
                'refId' => 'ref_' . substr(preg_replace('/[^A-Za-z0-9]/', '', $orderNumber), 0, 15),
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => $amount,
                    'payment' => [
                        'creditCard' => [
                            'cardNumber' => $cardNumber,
                            'expirationDate' => $formattedExpiry,
                            'cardCode' => $cardCode,
                        ]
                    ],
                    'order' => [
                        'invoiceNumber' => substr($orderNumber, 0, 20),
                        'description' => substr((string) ($params['description'] ?? 'Auto Vehicle Parts Order #' . $orderNumber), 0, 255),
                    ],
                    'customer' => [
                        'email' => $params['customer_email'] ?? ($params['customer']['email'] ?? null),
                    ],
                    'billTo' => [
                        'firstName' => substr($firstName, 0, 50),
                        'lastName' => substr($lastName, 0, 50),
                        'address' => substr((string) ($params['shipping_address'] ?? ($params['billing']['address'] ?? '')), 0, 60),
                        'city' => substr((string) ($params['city'] ?? ($params['billing']['city'] ?? '')), 0, 40),
                        'state' => substr((string) ($params['state'] ?? ($params['billing']['state'] ?? '')), 0, 40),
                        'zip' => substr((string) ($params['zip_code'] ?? ($params['billing']['zip'] ?? '')), 0, 20),
                        'country' => 'USA',
                        'phoneNumber' => substr((string) ($params['customer_phone'] ?? ($params['customer']['phone'] ?? '')), 0, 25),
                    ],
                    'shipTo' => [
                        'firstName' => substr($firstName, 0, 50),
                        'lastName' => substr($lastName, 0, 50),
                        'address' => substr((string) ($params['shipping_address'] ?? ($params['shipping']['address'] ?? '')), 0, 60),
                        'city' => substr((string) ($params['city'] ?? ($params['shipping']['city'] ?? '')), 0, 40),
                        'state' => substr((string) ($params['state'] ?? ($params['shipping']['state'] ?? '')), 0, 40),
                        'zip' => substr((string) ($params['zip_code'] ?? ($params['shipping']['zip'] ?? '')), 0, 20),
                        'country' => 'USA',
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->endpoint, $payload);

            // Strip UTF-8 Byte Order Mark (BOM) if present from Authorize.Net
            $body = trim($response->body(), "\xEF\xBB\xBF");
            $data = json_decode($body, true);

            if (!$data) {
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'error' => 'Invalid response received from payment gateway server.',
                    'response_code' => 'INVALID_GATEWAY_RESPONSE'
                ];
            }

            // Check Top-Level Status
            $resultCode = $data['messages']['resultCode'] ?? null;
            $txResponse = $data['transactionResponse'] ?? null;

            if ($resultCode === 'Ok' && $txResponse) {
                $responseCode = $txResponse['responseCode'] ?? null;
                $transId = $txResponse['transId'] ?? null;

                if ($responseCode == '1') {
                    // Approved
                    $authCode = $txResponse['authCode'] ?? null;
                    $desc = $txResponse['messages'][0]['description'] ?? 'Transaction Approved.';
                    return [
                        'success' => true,
                        'transaction_id' => $transId,
                        'auth_code' => $authCode,
                        'message' => $desc,
                        'raw' => $data
                    ];
                } elseif ($responseCode == '2') {
                    // Declined
                    $errorText = $txResponse['errors'][0]['errorText'] ?? 'The card was declined by the issuing bank.';
                    return [
                        'success' => false,
                        'transaction_id' => $transId,
                        'error' => $errorText,
                        'response_code' => 'DECLINED',
                        'raw' => $data
                    ];
                } else {
                    // Error / Held
                    $errorText = $txResponse['errors'][0]['errorText'] 
                        ?? ($txResponse['messages'][0]['description'] ?? 'Card authorization error.');
                    return [
                        'success' => false,
                        'transaction_id' => $transId,
                        'error' => $errorText,
                        'response_code' => 'ERROR',
                        'raw' => $data
                    ];
                }
            }

            // Top-Level Error
            $topError = $txResponse['errors'][0]['errorText'] 
                ?? ($data['messages']['message'][0]['text'] ?? 'Payment processing failed. Please check card details.');

            return [
                'success' => false,
                'transaction_id' => $txResponse['transId'] ?? null,
                'error' => $topError,
                'response_code' => 'GATEWAY_ERROR',
                'raw' => $data
            ];

        } catch (\Throwable $e) {
            Log::error('Authorize.Net Payment Exception: ' . $e->getMessage(), [
                'order' => $orderNumber,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'transaction_id' => null,
                'error' => 'Unable to connect to payment processor. Please verify network connection or try again.',
                'response_code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Normalize card expiration date to MMYY or YYYY-MM
     *
     * @param string $raw
     * @return string
     */
    protected function formatExpirationDate(string $raw): string
    {
        $clean = preg_replace('/\D/', '', $raw);

        // e.g. "0526" (MMYY)
        if (strlen($clean) === 4) {
            return $clean;
        }

        // e.g. "052026" (MMYYYY) -> "0526"
        if (strlen($clean) === 6) {
            $month = substr($clean, 0, 2);
            $year = substr($clean, 4, 2);
            return $month . $year;
        }

        // e.g. "202605" (YYYYMM) -> "0526"
        if (strlen($clean) === 6 && substr($clean, 0, 2) === '20') {
            $year = substr($clean, 2, 2);
            $month = substr($clean, 4, 2);
            return $month . $year;
        }

        return $raw;
    }
}
