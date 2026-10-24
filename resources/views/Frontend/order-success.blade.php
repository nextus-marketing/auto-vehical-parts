@extends('layouts.frontend')
@section('title') Order Confirmation - {{ $order->order_number }} | Auto Vehicle Parts @endsection
@section('content')
<main>
    <section class="order-confirmation-area pt-100 pb-100 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow border-0 rounded-3 overflow-hidden">
                        <div class="card-header bg-success text-white py-4 text-center">
                            <i class="fas fa-check-circle fa-4x mb-3 text-white"></i>
                            <h2 class="h3 fw-bold mb-1 text-white">Payment Received &amp; Order Confirmed!</h2>
                            <p class="mb-0 text-white-50">Thank you for your order, {{ $order->customer_name }}. Your invoice is verified.</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 bg-light rounded mb-4">
                                <div>
                                    <small class="text-muted d-block">Order Reference Number</small>
                                    <strong class="fs-5 text-primary font-monospace">{{ $order->order_number }}</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Transaction ID</small>
                                    <code class="text-dark">{{ $order->transaction_id }}</code>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Payment Status</small>
                                    <span class="badge bg-success px-2 py-1"><i class="fas fa-check"></i> {{ $order->payment_status }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('order.invoice', ['order_number' => $order->order_number]) }}" class="btn btn-sm btn-danger px-3 shadow-sm" style="background:#e81c2e; border:none;">
                                        <i class="fas fa-file-pdf me-1"></i> Download Invoice (PDF)
                                    </a>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3"><i class="fas fa-box text-danger me-2"></i> Ordered Auto Parts Items</h5>
                            <div class="table-responsive border rounded mb-4">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Part Description</th>
                                            <th>Part #</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Amount ($ USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td><strong>{{ $item->product_name }}</strong></td>
                                                <td><code>{{ $item->part_number ?: 'OEM-STANDARD' }}</code></td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end fw-bold">${{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end">Parts Subtotal:</td>
                                            <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->discount_amount > 0)
                                            <tr class="text-success">
                                                <td colspan="3" class="text-end">Coupon Discount ({{ $order->coupon_code }}):</td>
                                                <td class="text-end fw-bold">-${{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-end">Freight Shipping:</td>
                                            <td class="text-end text-success fw-bold">FREE LIFTGATE</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">Sales Tax:</td>
                                            <td class="text-end">${{ number_format($order->tax_amount, 2) }}</td>
                                        </tr>
                                        <tr class="fs-5 fw-bold">
                                            <td colspan="3" class="text-end text-dark">Total Paid (USD):</td>
                                            <td class="text-end text-danger">${{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i> Delivery Address</h6>
                                        <p class="mb-0 text-muted small">
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}<br>
                                            {{ $order->country }}<br>
                                            Phone: {{ $order->customer_phone }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-car text-primary me-1"></i> Vehicle Fitment Information</h6>
                                        <p class="mb-0 text-muted small">
                                            <strong>Vehicle:</strong> {{ $order->vehicle_details ?: 'Not specified' }}<br>
                                            <strong>VIN:</strong> {{ $order->vin_number ?: 'Not provided' }}<br>
                                            <strong>Warranty:</strong> 1-Year Comprehensive Replacement Guarantee
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center pt-2 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                <a href="{{ route('order.invoice', ['order_number' => $order->order_number]) }}" class="btn btn-danger px-4 shadow-sm" style="background:#e81c2e; border:none;">
                                    <i class="fas fa-file-pdf me-1"></i> Download Invoice (PDF)
                                </a>
                                <a href="{{ route('order.invoice', ['order_number' => $order->order_number]) }}?preview=1" target="_blank" class="btn btn-outline-secondary px-3">
                                    <i class="fas fa-eye me-1"></i> View Invoice
                                </a>
                                <a href="/shop" class="btn btn-primary px-4" style="background:#0c2957; border:none;">
                                    <i class="fas fa-store me-1"></i> Continue Shopping
                                </a>
                                <a href="/" class="btn btn-outline-secondary px-4">
                                    Return to Home
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
