@extends('layouts.admin')
@section('title')
    {{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }} | Auto Vehicle Parts
@endsection
@section('content')
<form method="POST"
    action="{{ isset($coupon) ? route('admin.coupons.update', ['coupon' => $coupon->id]) : route('admin.coupons.store') }}"
    autocomplete="off" id="coupons-form">
    @csrf
    @if(isset($coupon))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti ti-ticket me-1"></i> {{ isset($coupon) ? 'Edit Coupon Code' : 'Create New Coupon Code' }}
                    </h5>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to Coupons
                    </a>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase font-monospace" name="code" 
                                placeholder="e.g. WELCOME10, PARTS50, OEM100"
                                value="{{ isset($coupon) ? $coupon->code : '' }}" required />
                            <small class="text-muted">Enter uppercase code that customers will input at checkout</small>
                            <div id="code-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Campaign Title / Description</label>
                            <input type="text" class="form-control" name="title" 
                                placeholder="e.g. Summer Auto Parts Sale 15% Off"
                                value="{{ isset($coupon) ? $coupon->title : '' }}" />
                            <div id="title-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="discount_type" id="discount_type" required>
                                <option value="PERCENTAGE" {{ (isset($coupon) && $coupon->discount_type == 'PERCENTAGE') ? 'selected' : '' }}>Percentage Discount (% Off)</option>
                                <option value="FIXED" {{ (isset($coupon) && $coupon->discount_type == 'FIXED') ? 'selected' : '' }}>Fixed Dollar Amount ($ USD Off)</option>
                            </select>
                            <div id="discount_type-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="val-symbol">{{ (isset($coupon) && $coupon->discount_type == 'FIXED') ? '$' : '%' }}</span>
                                <input type="number" step="0.01" class="form-control" name="discount_value" 
                                    placeholder="e.g. 10 or 50.00"
                                    value="{{ isset($coupon) ? $coupon->discount_value : '' }}" required />
                            </div>
                            <small class="text-muted">Enter 10 for 10% or 50.00 for $50.00 off</small>
                            <div id="discount_value-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Minimum Order Amount ($ USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="min_order_amount" 
                                    placeholder="0.00"
                                    value="{{ isset($coupon) ? $coupon->min_order_amount : '0.00' }}" />
                            </div>
                            <small class="text-muted">Minimum cart subtotal before coupon can apply</small>
                            <div id="min_order_amount-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Expiration Date</label>
                            <input type="date" class="form-control" name="expires_at" 
                                value="{{ isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}" />
                            <small class="text-muted">Leave blank for no expiration</small>
                            <div id="expires_at-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Usage Limit</label>
                            <input type="number" class="form-control" name="usage_limit" 
                                placeholder="Unlimited"
                                value="{{ isset($coupon) ? $coupon->usage_limit : '' }}" />
                            <small class="text-muted">Max total redemptions permitted</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status">
                                <option value="ACTIVE" {{ (!isset($coupon) || $coupon->status == 'ACTIVE') ? 'selected' : '' }}>Active &amp; Usable</option>
                                <option value="INACTIVE" {{ (isset($coupon) && $coupon->status == 'INACTIVE') ? 'selected' : '' }}>Inactive / Paused</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4" id="btn-submit">
                        <i class="ti ti-device-floppy me-1"></i> Save Coupon
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#discount_type').on('change', function() {
            if ($(this).val() === 'FIXED') {
                $('#val-symbol').text('$');
            } else {
                $('#val-symbol').text('%');
            }
        });

        $('#coupons-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            var submitBtn = $('#btn-submit');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    toastr.success(response.message || 'Coupon saved successfully!');
                    setTimeout(function() {
                        window.location.href = "{!! route('admin.coupons.index') !!}";
                    }, 1000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Save Coupon');
                    toastr.error('Please fix errors in the form.');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value[0]);
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
