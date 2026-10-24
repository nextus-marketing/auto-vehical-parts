<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 28px 32px 32px 32px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.45;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 20px;
            border-bottom: 2px solid #0c2957;
            padding-bottom: 12px;
        }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #0c2957;
            margin-top: 4px;
        }
        .company-sub {
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.35;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 800;
            color: #0c2957;
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .invoice-meta {
            text-align: right;
            font-size: 10px;
            color: #475569;
            margin-top: 4px;
        }
        .invoice-meta strong {
            color: #0c2957;
        }
        .badge-paid {
            display: inline-block;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table {
            margin-bottom: 18px;
        }
        .info-card {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 12px;
            background-color: #f8fafc;
            vertical-align: top;
        }
        .info-title {
            font-size: 10.5px;
            font-weight: bold;
            color: #0c2957;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .items-table {
            width: 100%;
            border: 1px solid #cbd5e1;
            margin-bottom: 16px;
        }
        .items-table th {
            background-color: #0c2957;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            letter-spacing: 0.3px;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-table {
            width: 100%;
            margin-top: 4px;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 10.5px;
        }
        .summary-label {
            text-align: right;
            font-weight: 600;
            color: #475569;
            width: 80%;
        }
        .summary-value {
            text-align: right;
            font-weight: bold;
            color: #1e293b;
            width: 20%;
        }
        .total-row td {
            border-top: 2px solid #0c2957;
            border-bottom: 2px solid #0c2957;
            padding: 7px 8px;
            font-size: 13px;
            font-weight: 800;
            color: #e81c2e;
            background-color: #fef2f2;
        }
        .warranty-box {
            border: 1px dashed #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            padding: 10px 12px;
            margin-top: 18px;
            font-size: 9.5px;
            color: #475569;
            line-height: 1.4;
        }
        .footer-note {
            text-align: center;
            margin-top: 22px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Header with Official Logo -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                @if(!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" style="max-height: 44px; width: auto;" alt="Auto Vehicle Parts" />
                @else
                    <div style="font-size: 20px; font-weight: bold; color: #0c2957;">Auto Vehicle Parts</div>
                @endif
                <div class="company-name">Auto Vehicle Parts LLC</div>
                <div class="company-sub">
                    9620 Hillock Ct &bull; Burke, VA 22015, USA<br>
                    Phone: +1-888-370-9806<br>
                    Email: sales@autovehicleparts.com &bull; Web: autovehicleparts.com
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <div class="invoice-title">Invoice / Receipt</div>
                <div class="invoice-meta">
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Invoice Date:</strong> {{ $order->created_at->format('F d, Y') }}<br>
                    <strong>Payment Status:</strong> <span class="badge-paid">{{ $order->payment_status }}</span><br>
                    <strong>Payment Method:</strong> {{ $order->payment_method }}<br>
                    <strong>Transaction Ref:</strong> {{ $order->transaction_id ?: 'N/A' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Billing, Shipping & Vehicle Fitment Info -->
    <table class="info-table">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="info-card">
                    <div class="info-title">Billed &amp; Shipped To:</div>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}<br>
                    {{ $order->country }}<br>
                    <strong>Phone:</strong> {{ $order->customer_phone }}<br>
                    <strong>Email:</strong> {{ $order->customer_email }}
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="info-card">
                    <div class="info-title">Vehicle &amp; Warranty Details:</div>
                    <strong>Vehicle:</strong> {{ $order->vehicle_details ?: 'OEM Standard Direct Fit' }}<br>
                    <strong>VIN Number:</strong> {{ $order->vin_number ?: 'Verified Pre-Shipment' }}<br>
                    <strong>Warranty:</strong> 1-Year Comprehensive Replacement Guarantee<br>
                    <strong>Freight Status:</strong> Inspected &amp; Liftgate Service Direct to Shop
                </div>
            </td>
        </tr>
    </table>

    <!-- Itemized Auto Parts Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 48%;">Auto Part Description</th>
                <th style="width: 18%;">OEM Part #</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 18%; text-align: right;">Total ($ USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                    </td>
                    <td><code>{{ $item->part_number ?: 'OEM-STANDARD' }}</code></td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary & Financial Calculation Breakdown -->
    <table class="summary-table">
        <tr>
            <td class="summary-label">Parts Subtotal:</td>
            <td class="summary-value">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount_amount > 0)
            <tr>
                <td class="summary-label" style="color: #16a34a;">Coupon Discount ({{ $order->coupon_code }}):</td>
                <td class="summary-value" style="color: #16a34a;">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td class="summary-label">Freight Shipping (Liftgate USA):</td>
            <td class="summary-value" style="color: #16a34a;">FREE</td>
        </tr>
        <tr>
            <td class="summary-label">Sales Tax:</td>
            <td class="summary-value">${{ number_format($order->tax_amount, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td class="summary-label" style="color: #0c2957;">Total Paid (USD):</td>
            <td class="summary-value" style="color: #e81c2e;">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <!-- Warranty & Policy Guarantee Notice -->
    <div class="warranty-box">
        <strong style="color: #0c2957;">Warranty &amp; Guarantee Terms:</strong><br>
        All certified OEM recycled automotive components are backed by our standard 1-Year Comprehensive Replacement Guarantee. Every assembly is spin and compression inspected prior to shipment. To initiate a return, claim replacement, or speak with an assigned parts specialist, please contact our support desk with your invoice reference number <strong>{{ $order->order_number }}</strong>.
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        Thank you for choosing Auto Vehicle Parts LLC! Sourced from verified automotive recyclers across North America.<br>
        This is an official computer-generated tax invoice. No signature required.
    </div>

</body>
</html>
