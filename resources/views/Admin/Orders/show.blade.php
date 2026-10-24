@extends('layouts.admin')
@section('title') Order {{ $order->order_number }} | Auto Vehicle Parts @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="ti ti-receipt me-1"></i> Order Details: <span class="text-primary font-monospace">{{ $order->order_number }}</span>
                        </h5>
                        <small class="text-muted">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('order.invoice', ['order_number' => $order->order_number]) }}" class="btn btn-danger btn-sm" download>
                            <i class="ti ti-file-download me-1"></i> Download Invoice (PDF)
                        </a>
                        <a href="{{ route('order.invoice', ['order_number' => $order->order_number]) }}?preview=1" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-eye me-1"></i> Preview PDF
                        </a>
                        <button class="btn btn-outline-danger btn-sm" id="btn-delete-order" data-id="{{ $order->id }}" data-name="{{ $order->order_number }}">
                            <i class="ti ti-trash me-1"></i> Delete Order
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Banner -->
                    <div class="alert {{ $order->payment_status === 'SUCCESS' ? 'alert-success' : ($order->payment_status === 'FAILED' ? 'alert-danger' : 'alert-warning') }} d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="alert-heading mb-1 fw-bold">
                                Payment Status: {{ $order->payment_status }} 
                                &bull; Fulfillment: {{ $order->order_status }}
                            </h6>
                            @if($order->payment_status === 'FAILED' && $order->failure_reason)
                                <p class="mb-0 text-danger fw-semibold"><i class="ti ti-alert-circle me-1"></i> Failure Reason: {{ $order->failure_reason }}</p>
                            @else
                                <p class="mb-0">Transaction Ref: <code>{{ $order->transaction_id ?: 'N/A' }}</code> (Method: {{ $order->payment_method }})</p>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <select id="update_order_status" class="form-select form-select-sm" style="width:160px;">
                                @foreach(['PROCESSING', 'CONFIRMED', 'SHIPPED', 'DELIVERED', 'CANCELLED', 'FAILED'] as $st)
                                    <option value="{{ $st }}" {{ $order->order_status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-dark" onclick="saveOrderStatus()"><i class="ti ti-check"></i> Update</button>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <!-- Customer & Shipping -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 bg-light-subtle">
                                <h6 class="fw-bold text-primary mb-3"><i class="ti ti-user me-1"></i> Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>
                                <p class="mb-1"><strong>Phone:</strong> <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></p>
                                <hr>
                                <h6 class="fw-bold text-primary mb-2"><i class="ti ti-truck-delivery me-1"></i> Shipping Address</h6>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                                <p class="mb-0">{{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}</p>
                                <p class="text-muted small">{{ $order->country }}</p>
                            </div>
                        </div>

                        <!-- Vehicle & VIN -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 bg-light-subtle">
                                <h6 class="fw-bold text-primary mb-3"><i class="ti ti-car me-1"></i> Vehicle &amp; Part Fitment</h6>
                                <p class="mb-2"><strong>Vehicle:</strong> {{ $order->vehicle_details ?: 'Not specified' }}</p>
                                <p class="mb-2">
                                    <strong>VIN Number:</strong> 
                                    @if($order->vin_number)
                                        <span class="badge bg-secondary font-monospace fs-3">{{ $order->vin_number }}</span>
                                    @else
                                        <span class="text-muted">None provided</span>
                                    @endif
                                </p>
                                <hr>
                                <h6 class="fw-bold text-primary mb-2"><i class="ti ti-notes me-1"></i> Order Notes</h6>
                                <p class="text-muted mb-0">{{ $order->notes ?: 'No special instructions recorded.' }}</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="col-12">
                            <h6 class="fw-bold mb-3"><i class="ti ti-box me-1"></i> Ordered Auto Parts Items</h6>
                            <div class="table-responsive border rounded">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Part Description</th>
                                            <th>Part # / SKU</th>
                                            <th class="text-center">Unit Price ($ USD)</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if($item->product)
                                                        <br><a href="/shop/{{ $item->product->slug }}" target="_blank" class="small text-primary"><i class="ti ti-external-link"></i> View Shop Listing</a>
                                                    @endif
                                                </td>
                                                <td><code>{{ $item->part_number ?: 'OEM-STANDARD' }}</code></td>
                                                <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end fw-bold">${{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">No specific line items recorded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal:</td>
                                            <td class="text-end fw-semibold">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->discount_amount > 0)
                                            <tr class="text-success">
                                                <td colspan="4" class="text-end">Coupon Discount ({{ $order->coupon_code }}):</td>
                                                <td class="text-end fw-bold">-${{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" class="text-end">Freight Shipping:</td>
                                            <td class="text-end text-success fw-semibold">{{ $order->shipping_amount > 0 ? '$' . number_format($order->shipping_amount, 2) : 'FREE' }}</td>
                                        </tr>
                                        @if($order->tax_amount > 0)
                                            <tr>
                                                <td colspan="4" class="text-end">Estimated Sales Tax:</td>
                                                <td class="text-end">${{ number_format($order->tax_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="fs-4">
                                            <td colspan="4" class="text-end fw-bold text-dark">Total Charged (USD):</td>
                                            <td class="text-end fw-bold text-primary">${{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function saveOrderStatus() {
        var status = $('#update_order_status').val();
        $.ajax({
            url: "{{ route('admin.orders.update.status', ['id' => $order->id]) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_status: status
            },
            success: function(res) {
                toastr.success(res.message);
                setTimeout(function() { location.reload(); }, 800);
            },
            error: function() {
                toastr.error('Failed to update status');
            }
        });
    }
    $('#btn-delete-order').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        if (!confirm('Are you sure you want to permanently delete order "' + name + '" and all its items? This cannot be undone.')) return;
        var btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Deleting...');
        $.ajax({
            type: 'DELETE',
            url: '/admin/orders/' + id,
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                toastr.success(response.message || 'Order deleted successfully.');
                setTimeout(function() {
                    window.location.href = "{{ route('admin.orders.index') }}";
                }, 1200);
            },
            error: function() {
                toastr.error('Failed to delete order.');
                btn.prop('disabled', false).html('<i class="ti ti-trash me-1"></i> Delete Order');
            }
        });
    });
</script>
@endsection
