<!doctype html>
<html class="no-js" lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="description" content="Shop certified tested OEM used car auto parts including engines, transmissions, headlights, and brakes with nationwide warranty.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" type="image/x-icon" href="/frontend/logo/favicon.png">

		<!-- CSS here -->
        <link rel="stylesheet" href="/frontend/css/bootstrap.min.css">
        <link rel="stylesheet" href="/frontend/css/animate.min.css">
        <link rel="stylesheet" href="/frontend/css/magnific-popup.css">
        <link rel="stylesheet" href="/frontend/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="/frontend/css/dripicons.css">
        <link rel="stylesheet" href="/frontend/css/slick.css">
        <link rel="stylesheet" href="/frontend/css/meanmenu.css">
        <link rel="stylesheet" href="/frontend/css/default.css">
        <link rel="stylesheet" href="/frontend/css/style.css">
        <link rel="stylesheet" href="/frontend/css/my.css">
        <link rel="stylesheet" href="/frontend/css/responsive.css">

        <style>
            /* Cart Drawer Styles */
            .cart-drawer-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 99998;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            .cart-drawer-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            .cart-drawer {
                position: fixed;
                top: 0;
                right: -420px;
                width: 420px;
                max-width: 90vw;
                height: 100%;
                background: #fff;
                z-index: 99999;
                box-shadow: -5px 0 25px rgba(0,0,0,0.25);
                transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                display: flex;
                flex-direction: column;
            }
            .cart-drawer.active {
                right: 0;
            }
            .cart-drawer-header {
                padding: 18px 20px;
                background: #0c2957;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .cart-drawer-body {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }
            .cart-drawer-footer {
                padding: 20px;
                background: #f8f9fa;
                border-top: 1px solid #e9ecef;
            }
            .cart-item-row {
                display: flex;
                gap: 12px;
                padding: 12px 0;
                border-bottom: 1px solid #f0f0f0;
                align-items: center;
            }
            .cart-item-img {
                width: 65px;
                height: 65px;
                object-fit: cover;
                border-radius: 6px;
                border: 1px solid #eee;
            }
            .cart-toast {
                position: fixed;
                bottom: 25px;
                right: 25px;
                background: #0c2957;
                color: #fff;
                padding: 14px 22px;
                border-radius: 8px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                z-index: 999999;
                display: none;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                border-left: 4px solid #e81c2e;
            }
        </style>
    </head>
    <body>
        <!-- header -->
        <header class="header-area header-three">  
           <div class="header-top second-header d-none d-md-block">
                <div class="container">
                    <div class="row align-items-center">      
                        <div class="col-lg-6 col-md-6 d-none d-lg-block">
                             <div class="header-cta">
                               <ul>                                   
                                    <li>
                                        <i class="icon fal fa-envelope"></i>
                                        <a href="mailto:sales@autovehicleparts.com" style="color:inherit;"><span>sales@autovehicleparts.com</span></a>
                                    </li>
                                    <li>
                                        <i class="icon fal fa-phone"></i>
                                        <a href="tel:+18883709806" style="color:inherit;"><span>+1-888-370-9806</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 text-right">
                            <div class="header-social">
                                <span>Certified OEM Recycled Car &amp; Truck Auto Parts Nationwide (USA)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <div id="header-sticky" class="menu-area">
                <div class="container">
                    <div class="second-menu">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="/"><img src="/frontend/logo/logo.png" alt="Auto Vehicle Parts Logo" style="max-height: 48px;"></a>
                                </div>
                            </div>
                           <div class="col-xl-7 col-lg-7">
                                <div class="main-menu text-center text-xl-right">
                                    <nav id="mobile-menu">
                                          <ul>
                                            <li><a href="/">Home</a></li>
                                            <li><a href="/about">About Us</a></li>
                                            
                                            <!-- Dynamic Categories Dropdown -->
                                            <li class="has-sub"><a href="/shop">Shop Auto Parts</a>
												<ul>
                                                    <li><a href="/shop"><strong>View All Auto Parts</strong></a></li>
                                                    @php
                                                        $navCategories = \App\Models\Category::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
                                                    @endphp
                                                    @forelse($navCategories as $navCat)
                                                        <li><a href="/shop?category={{ $navCat->slug }}">{{ $navCat->name }}</a></li>
                                                    @empty
                                                        <li><a href="/shop">Browse Catalog</a></li>
                                                    @endforelse
                                                </ul>
											</li>
                                            <li><a href="/contact">Contact Us</a></li>                                               
                                        </ul>
                                    </nav>
                                </div>
                            </div>   
                            
                            <!-- Right Header Actions (Replaced Search with Cart Icon & Calculation) -->
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                               <div class="right-menu d-flex align-items-center justify-content-end">
                                    <ul class="d-flex align-items-center mb-0">
                                        <!-- Cart Icon Button -->
                                        <li class="me-3"> 
                                            <a href="javascript:void(0)" onclick="toggleCartDrawer()" class="cart-trigger-btn" title="View Cart & Calculation" style="position:relative; display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; background:#fff; color:#0c2957; border:2px solid #e81c2e; border-radius:50%; font-size:18px; text-decoration:none; transition:all 0.3s ease; box-shadow:0 3px 8px rgba(0,0,0,0.1);">
                                                <i class="fas fa-shopping-cart text-danger"></i>
                                                <span class="cart-badge-count" id="headerCartCount" style="position:absolute; top:-5px; right:-5px; background:#0c2957; color:#fff; font-size:11px; font-weight:700; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff;">0</span>
                                            </a>
                                        </li>

                                        <!-- Hotline -->
                                        <li>
                                            <div class="text">
                                                <span>Toll-Free Hotline</span>
                                                <a href="tel:+18883709806" style="color:inherit; text-decoration:none;"><strong>+1-888-370-9806</strong></a>
                                            </div>
                                        </li>
                                    </ul>
                                 </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="mobile-menu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header-end -->

        <!-- main-area -->
        @yield('content')
        <!-- main-area-end -->

        <!-- footer -->
        <footer class="footer-bg footer-p pt-90" style="background-color: #0c2957; background-image: url(/frontend/img/bg/footer-bg.png);">
            <div class="footer-top pb-70">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>About Auto Vehicle Parts</h2>
                                </div>
                                <div class="f-contact">
                                     <p>Auto Vehicle Parts is America's trusted supplier of quality-tested OEM used car parts. We stock certified used engines, transmissions, body panels, and electrical assemblies with industry-leading warranties and fast nationwide shipping.</p>
                                </div>
                                 <div class="footer-social mt-10">                                    
                                    <a href="https://facebook.com" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://instagram.com" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                                    <a href="https://twitter.com" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                                </div>    
                            </div>
                        </div>
						<div class="col-xl-2 col-lg-2 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>Quick Links</h2>
                                </div>
                                <div class="footer-link">
                                    <ul>                                        
                                        <li><a href="/">Home</a></li>
                                        <li><a href="/about">About Us</a></li>
                                        <li><a href="/shop">Shop Auto Parts</a></li>
                                        <li><a href="/cart">Cart &amp; Calculation</a></li>
                                        <li><a href="/checkout">Secure Checkout</a></li>
                                        <li><a href="/contact">Get Free Quote</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>Customer Support</h2>
                                </div>
                                <div class="footer-link">
                                    <ul>
                                        <li><a href="/return-policy">Return Policy &amp; Warranty</a></li>
                                        <li><a href="/terms-and-condition">Terms &amp; Conditions</a></li>
                                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                                        <li><a href="/contact">Help &amp; FAQ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>National Headquarters</h2>
                                </div>
                                <div class="f-contact">
                                    <ul>
                                        <li>
                                            <i class="icon fal fa-map-marker-alt"></i>
                                            <span>9620 Hillock Ct Burke, VA 22015, USA</span>
                                        </li>
                                        <li>
                                            <i class="icon fal fa-phone"></i>
                                            <a href="tel:+18883709806" style="color:inherit;"><span>+1-888-370-9806</span></a>
                                        </li>
                                        <li>
                                            <i class="icon fal fa-envelope"></i>
                                            <a href="mailto:sales@autovehicleparts.com" style="color:inherit;"><span>sales@autovehicleparts.com</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-wrap">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                          <div class="copy-text">
                                 Copyright &copy; Auto Vehicle Parts 2026. All rights reserved. Sourced from certified automotive dismantlers.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer-end -->

        <!-- Slide-out Shopping Cart & Calculation Drawer -->
        <div class="cart-drawer-overlay" id="cartOverlay" onclick="toggleCartDrawer()"></div>
        <div class="cart-drawer" id="cartDrawer">
            <div class="cart-drawer-header">
                <h5 class="mb-0 text-white font-weight-bold">
                    <i class="fas fa-shopping-cart me-2"></i> Auto Parts Cart (<span id="cartItemCount">0</span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="toggleCartDrawer()" style="background:none; border:none; color:#fff; font-size:22px; cursor:pointer;">&times;</button>
            </div>

            <div class="cart-drawer-body" id="cartDrawerBody">
                <!-- Cart items dynamically rendered via Javascript -->
                <div id="cartItemsContainer">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                        <p>Your auto parts cart is currently empty.</p>
                        <a href="/shop" class="btn btn-sm btn-danger mt-2" onclick="toggleCartDrawer()">Shop Used Auto Parts</a>
                    </div>
                </div>
            </div>

            <div class="cart-drawer-footer" id="cartDrawerFooter">
                <!-- Promo Coupon Box -->
                <div class="coupon-section mb-3">
                    <label class="small text-muted fw-bold d-block mb-1"><i class="fas fa-tag text-danger"></i> Have a Promo Coupon Code?</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control text-uppercase" id="drawerCouponInput" placeholder="e.g. WELCOME10, PARTS50">
                        <button class="btn btn-dark" type="button" onclick="applyDrawerCoupon()">Apply</button>
                    </div>
                    <div id="drawerCouponStatus" class="small mt-1"></div>
                </div>

                <!-- Financial Calculation (USD) -->
                <div class="calculation-breakdown border-top pt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Subtotal:</span>
                        <span id="drawerSubtotal" class="fw-bold text-dark">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between small text-success mb-1" id="drawerDiscountRow" style="display:none !important;">
                        <span>Coupon Discount:</span>
                        <span id="drawerDiscount" class="fw-bold">-$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Freight Shipping (USA):</span>
                        <span class="text-success fw-bold">FREE LIFTGATE</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Estimated Sales Tax (7%):</span>
                        <span id="drawerTax">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mb-3">
                        <span class="fw-bold fs-5 text-dark">Total (USD):</span>
                        <span id="drawerTotal" class="fw-bold fs-5 text-danger">$0.00</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <a href="/checkout" class="btn btn-danger btn-block py-2 fw-bold text-uppercase" style="background:#e81c2e; border:none;">
                        Proceed to Secure Checkout <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                    <a href="/cart" class="btn btn-outline-secondary btn-sm btn-block text-center mt-1">
                        View Full Cart Details
                    </a>
                </div>
            </div>
        </div>

        <!-- Global Toast Alert -->
        <div class="cart-toast" id="globalCartToast">
            <i class="fas fa-check-circle text-success fa-lg"></i>
            <span id="toastMessage">Product added to your cart</span>
        </div>

		<!-- JS here -->
        <script src="/frontend/js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="/frontend/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="/frontend/js/popper.min.js"></script>
        <script src="/frontend/js/bootstrap.min.js"></script>
        <script src="/frontend/js/slick.min.js"></script>
        <script src="/frontend/js/ajax-form.js"></script>
        <script src="/frontend/js/paroller.js"></script>
        <script src="/frontend/js/wow.min.js"></script>
        <script src="/frontend/js/js_isotope.pkgd.min.js"></script>
        <script src="/frontend/js/imagesloaded.min.js"></script>
        <script src="/frontend/js/parallax.min.js"></script>
        <script src="/frontend/js/jquery.waypoints.min.js"></script>
        <script src="/frontend/js/jquery.counterup.min.js"></script>
        <script src="/frontend/js/jquery.scrollUp.min.js"></script>
        <script src="/frontend/js/jquery.meanmenu.min.js"></script>
        <script src="/frontend/js/parallax-scroll.js"></script>
        <script src="/frontend/js/jquery.magnific-popup.min.js"></script>
        <script src="/frontend/js/element-in-view.js"></script>
        <script src="/frontend/js/main.js"></script>

        <!-- Dynamic Cart & Calculation JavaScript -->
        <script>
            function toggleCartDrawer() {
                $('#cartDrawer').toggleClass('active');
                $('#cartOverlay').toggleClass('active');
            }

            function showToast(msg) {
                $('#toastMessage').text(msg);
                $('#globalCartToast').fadeIn(300).delay(2500).fadeOut(300);
            }

            function updateDrawerUI(cart) {
                $('#headerCartCount').text(cart.count || 0);
                $('#cartItemCount').text(cart.count || 0);
                $('#drawerSubtotal').text('$' + parseFloat(cart.subtotal).toFixed(2));
                $('#drawerTax').text('$' + parseFloat(cart.tax).toFixed(2));
                $('#drawerTotal').text('$' + parseFloat(cart.total).toFixed(2));

                if (cart.discount > 0) {
                    $('#drawerDiscountRow').attr('style', 'display: flex !important;');
                    $('#drawerDiscount').text('-$' + parseFloat(cart.discount).toFixed(2));
                    if (cart.coupon) {
                        $('#drawerCouponStatus').html('<span class="text-success"><i class="fas fa-check"></i> Coupon <strong>' + cart.coupon.code + '</strong> applied! <a href="javascript:void(0)" onclick="removeDrawerCoupon()" class="text-danger ms-1">[Remove]</a></span>');
                    }
                } else {
                    $('#drawerDiscountRow').attr('style', 'display: none !important;');
                    $('#drawerCouponStatus').empty();
                }

                var container = $('#cartItemsContainer');
                var items = Object.values(cart.items || {});

                if (items.length === 0) {
                    container.html(`
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                            <p>Your auto parts cart is currently empty.</p>
                            <a href="/shop" class="btn btn-sm btn-danger mt-2" onclick="toggleCartDrawer()">Shop Used Auto Parts</a>
                        </div>
                    `);
                    $('#cartDrawerFooter').hide();
                } else {
                    $('#cartDrawerFooter').show();
                    var html = '';
                    items.forEach(function(item) {
                        html += `
                            <div class="cart-item-row">
                                <img src="${item.photo}" class="cart-item-img" alt="${item.name}">
                                <div style="flex:1;">
                                    <h6 class="mb-0" style="font-size:14px; line-height:1.2;">
                                        <a href="/shop/${item.slug}" style="color:#0c2957; text-decoration:none;">${item.name}</a>
                                    </h6>
                                    <small class="text-muted d-block">PN: ${item.part_number || 'OEM'}</small>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <div class="input-group input-group-sm" style="width: 80px;">
                                            <input type="number" class="form-control text-center p-1" value="${item.quantity}" min="1" onchange="updateDrawerItemQty(${item.id}, this.value)">
                                        </div>
                                        <strong class="text-danger">$${(item.price * item.quantity).toFixed(2)}</strong>
                                        <a href="javascript:void(0)" onclick="removeDrawerItem(${item.id})" class="text-muted hover-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.html(html);
                }
            }

            function loadCart() {
                $.get('/cart/data', function(res) {
                    if (res.status === 'success') {
                        updateDrawerUI(res.cart);
                    }
                });
            }

            function addToCartGlobal(productId, qty) {
                qty = qty || 1;
                $.ajax({
                    url: '/cart/add',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId,
                        quantity: qty
                    },
                    success: function(res) {
                        updateDrawerUI(res.cart);
                        showToast(res.message);
                        toggleCartDrawer();
                    },
                    error: function() {
                        alert('Error adding part to cart. Please try again.');
                    }
                });
            }

            function updateDrawerItemQty(productId, qty) {
                $.ajax({
                    url: '/cart/update',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId,
                        quantity: qty
                    },
                    success: function(res) {
                        updateDrawerUI(res.cart);
                    }
                });
            }

            function removeDrawerItem(productId) {
                $.ajax({
                    url: '/cart/remove',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId
                    },
                    success: function(res) {
                        updateDrawerUI(res.cart);
                        showToast('Item removed from cart.');
                    }
                });
            }

            function applyDrawerCoupon() {
                var code = $('#drawerCouponInput').val().trim();
                if (!code) {
                    $('#drawerCouponStatus').html('<span class="text-danger">Please enter a coupon code.</span>');
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
                        updateDrawerUI(res.cart);
                        showToast(res.message);
                    },
                    error: function(xhr) {
                        var err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Invalid coupon code';
                        $('#drawerCouponStatus').html('<span class="text-danger">' + err + '</span>');
                    }
                });
            }

            function removeDrawerCoupon() {
                $.ajax({
                    url: '/cart/remove-coupon',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        updateDrawerUI(res.cart);
                        showToast(res.message);
                    }
                });
            }

            $(document).ready(function() {
                loadCart();
            });
        </script>
    </body>
</html>