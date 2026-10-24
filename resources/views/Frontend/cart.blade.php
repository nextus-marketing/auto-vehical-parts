@extends('layouts.frontend')
@section('title') Shopping Cart &amp; Order Calculation | Auto Vehicle Parts @endsection
@section('content')
<main>
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Shopping Cart &amp; Calculation</h2>    
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->

    <section class="cart-area pt-80 pb-90">
        <div class="container">
            <div class="row">
                <!-- Cart Items List -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-shopping-cart text-danger me-2"></i> Selected Auto Parts ({{ $cartData['count'] }})
                            </h5>
                            <a href="/shop" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(empty($cartData['items']))
                                <div class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>Your shopping cart is empty</h5>
                                    <p class="text-muted">Explore our catalog of certified tested OEM used auto parts.</p>
                                    <a href="/shop" class="btn btn-danger mt-2" style="background:#e81c2e;">Shop Auto Parts Now</a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Part Description</th>
                                                <th>Price ($ USD)</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Subtotal</th>
                                                <th class="text-center">Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cartData['items'] as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <img src="{{ $item['photo'] }}" class="rounded border" style="width:70px; height:70px; object-fit:cover;" alt="{{ $item['name'] }}">
                                                            <div>
                                                                <h6 class="mb-1" style="font-size:14px;">
                                                                    <a href="/shop/{{ $item['slug'] }}" class="text-dark text-decoration-none fw-bold">{{ $item['name'] }}</a>
                                                                </h6>
                                                                <small class="text-muted d-block">PN: {{ $item['part_number'] }}</small>
                                                                <small class="text-success"><i class="fas fa-shield-alt"></i> {{ $item['warranty'] }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold">${{ number_format($item['price'], 2) }}</td>
                                                    <td class="text-center">
                                                        <div class="input-group input-group-sm justify-content-center" style="width: 100px; margin:0 auto;">
                                                            <button class="btn btn-outline-secondary" onclick="updateItemQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</button>
                                                            <input type="text" class="form-control text-center p-1" value="{{ $item['quantity'] }}" readonly>
                                                            <button class="btn btn-outline-secondary" onclick="updateItemQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold text-dark">
                                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm text-danger" onclick="removeCartItem({{ $item['id'] }})" title="Remove item">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Calculation & Coupon Code Module -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                        <div class="card-header bg-dark text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i> Order Calculation (USD)</h5>
                        </div>
                        <div class="card-body">
                            <!-- Coupon Module -->
                            <div class="mb-4">
                                <label class="fw-bold text-dark mb-1 small"><i class="fas fa-tag text-danger me-1"></i> Apply Promo / Coupon Code</label>
                                <div class="input-group">
                                    <input type="text" id="cartPageCouponInput" class="form-control text-uppercase" placeholder="e.g. WELCOME10, PARTS50">
                                    <button class="btn btn-dark" type="button" onclick="applyCartPageCoupon()">Apply</button>
                                </div>
                                <div id="cartCouponFeedback" class="small mt-1"></div>

                                @if($cartData['coupon'])
                                    <div class="alert alert-success py-2 px-3 mt-2 d-flex justify-content-between align-items-center mb-0">
                                        <small><i class="fas fa-check-circle me-1"></i> <strong>{{ $cartData['coupon']['code'] }}</strong> applied</small>
                                        <button type="button" class="btn-close btn-sm" onclick="removeDrawerCoupon(); setTimeout(function(){ location.reload(); }, 400);"></button>
                                    </div>
                                @endif
                            </div>

                            <!-- Financial Breakdown -->
                            <div class="calculation-summary border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Parts Subtotal:</span>
                                    <span class="fw-bold">${{ number_format($cartData['subtotal'], 2) }}</span>
                                </div>

                                @if($cartData['discount'] > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Coupon Discount:</span>
                                        <span class="fw-bold">-${{ number_format($cartData['discount'], 2) }}</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Freight Shipping:</span>
                                    <span class="text-success fw-bold">FREE LIFTGATE</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Estimated Tax (7%):</span>
                                    <span>${{ number_format($cartData['tax'], 2) }}</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fs-5 fw-bold text-dark">Total Amount:</span>
                                    <span class="fs-4 fw-bold text-danger">${{ number_format($cartData['total'], 2) }} USD</span>
                                </div>

                                @if(!empty($cartData['items']))
                                    <a href="/checkout" class="btn btn-danger btn-lg w-100 fw-bold py-3 text-uppercase" style="background:#e81c2e; border:none;">
                                        Proceed to Secure Checkout <i class="fas fa-lock ms-2"></i>
                                    </a>
                                @endif

                                <div class="mt-3 text-center text-muted small">
                                    <i class="fas fa-shield-alt text-success me-1"></i> 256-Bit SSL Encrypted USA Checkout
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function updateItemQuantity(productId, qty) {
        $.ajax({
            url: '/cart/update',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: productId,
                quantity: qty
            },
            success: function() {
                location.reload();
            }
        });
    }

    function removeCartItem(productId) {
        $.ajax({
            url: '/cart/remove',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: productId
            },
            success: function() {
                location.reload();
            }
        });
    }

    function applyCartPageCoupon() {
        var code = $('#cartPageCouponInput').val().trim();
        if (!code) {
            $('#cartCouponFeedback').html('<span class="text-danger">Please enter a code.</span>');
            return;
        }

        $.ajax({
            url: '/cart/apply-coupon',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                code: code
            },
            success: function(res) {
                location.reload();
            },
            error: function(xhr) {
                var err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Invalid coupon code';
                $('#cartCouponFeedback').html('<span class="text-danger">' + err + '</span>');
            }
        });
    }
</script>
@endsection
