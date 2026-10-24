@extends('layouts.frontend')
@section('title') Transaction Failed - {{ $order->order_number }} | Auto Vehicle Parts @endsection
@section('content')
<main>
    <section class="order-failed-area pt-100 pb-100 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow border-0 rounded-3 overflow-hidden">
                        <div class="card-header bg-danger text-white py-4 text-center" style="background:#e81c2e !important;">
                            <i class="fas fa-times-circle fa-4x mb-3 text-white"></i>
                            <h2 class="h3 fw-bold mb-1 text-white">Payment Unsuccessful / Order Failed</h2>
                            <p class="mb-0 text-white-50">We were unable to complete your transaction with the payment details provided.</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <!-- Alert with Failure Reason -->
                            <div class="alert alert-danger p-3 mb-4">
                                <h6 class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Decline Reason:</h6>
                                <p class="mb-0">{{ $order->failure_reason ?: 'Payment gateway declined the authorization request. Please check your card balance, expiration date, or billing address.' }}</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap p-3 bg-light rounded mb-4">
                                <div>
                                    <small class="text-muted d-block">Recorded Order Number</small>
                                    <strong class="fs-5 text-dark font-monospace">{{ $order->order_number }}</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Transaction Ref</small>
                                    <code class="text-danger">{{ $order->transaction_id }}</code>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Payment Status</small>
                                    <span class="badge bg-danger px-2 py-1"><i class="fas fa-times"></i> FAILED</span>
                                </div>
                            </div>

                            <p class="text-muted">
                                Your order has been logged in our system as a <strong>Failed / Declined Order</strong>. Don't worry&mdash;your selected parts are still held for you temporarily. You can retry with a different credit card or contact our billing department by phone.
                            </p>

                            <h6 class="fw-bold mb-2">Order Attempt Summary:</h6>
                            <div class="p-3 border rounded bg-white mb-4">
                                <div class="d-flex justify-content-between text-muted mb-1">
                                    <span>Attempted Charge:</span>
                                    <span class="fw-bold text-dark">${{ number_format($order->total_amount, 2) }} USD</span>
                                </div>
                                <div class="d-flex justify-content-between text-muted mb-1">
                                    <span>Customer:</span>
                                    <span>{{ $order->customer_name }} ({{ $order->customer_email }})</span>
                                </div>
                                <div class="d-flex justify-content-between text-muted">
                                    <span>Vehicle Details:</span>
                                    <span>{{ $order->vehicle_details ?: 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-center flex-wrap pt-2">
                                <a href="/checkout" class="btn btn-danger px-4 py-2 fw-bold" style="background:#e81c2e; border:none;">
                                    <i class="fas fa-redo me-1"></i> Try Payment Again
                                </a>
                                <a href="tel:+18883709806" class="btn btn-primary px-4 py-2 fw-bold" style="background:#0c2957; border:none;">
                                    <i class="fas fa-phone-alt me-1"></i> Call Toll-Free: +1-888-370-9806
                                </a>
                                <a href="/shop" class="btn btn-outline-secondary px-3 py-2">
                                    Return to Shop
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
