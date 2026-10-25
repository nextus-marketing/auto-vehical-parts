@extends('layouts.frontend')
@section('title') Secure Checkout &amp; Payment ($ USD) | Auto Vehicle Parts @endsection
@section('content')
<style>
    .co { --red:#e81c2e; --red-dark:#c4121f; --ink:#1c1f26; --muted:#6b7280; --line:#e5e7eb; --soft:#f5f6f8; --ok:#198754; }
    .co-page { background:var(--soft); padding:48px 0 72px; }
    .co-box { background:#fff; border:1px solid var(--line); border-radius:10px; }
    .co-step { margin-bottom:16px; overflow:hidden; }
    .co-head { display:flex; align-items:center; gap:14px; padding:18px 22px; cursor:default; }
    .co-num { width:30px; height:30px; border-radius:50%; display:grid; place-items:center; font-weight:700; font-size:14px; background:var(--soft); color:var(--muted); flex:none; }
    .co-title { margin:0; font-size:18px; font-weight:700; color:var(--muted); flex-grow:1; }
    .co-summary-line { font-size:13px; color:var(--muted); font-weight:400; display:none; }
    .co-edit { display:none; background:none; border:0; color:var(--red); font-weight:600; font-size:14px; padding:0; }
    .co-body { display:none; padding:4px 22px 24px; border-top:1px solid var(--line); }
    .co-step.is-active .co-body { display:block; }
    .co-step.is-active .co-num { background:var(--red); color:#fff; }
    .co-step.is-active .co-title { color:var(--ink); }
    .co-step.is-done .co-num { background:var(--ok); color:#fff; }
    .co-step.is-done .co-title { color:var(--ink); }
    .co-step.is-done .co-edit { display:inline; }
    .co-step.is-done .co-summary-line { display:block; }
    .co .form-label { font-size:13px; font-weight:600; color:var(--ink); margin-bottom:5px; }
    .co .form-control { border-radius:8px; border:1px solid #d1d5db; padding:11px 13px; font-size:15px; }
    .co .form-control:focus { border-color:var(--red); box-shadow:0 0 0 3px rgba(232,28,46,.15); }
    .co-hint { font-size:12px; color:var(--muted); margin-top:4px; display:block; }
    .co-btn { background:var(--red); color:#fff; border:0; border-radius:8px; font-weight:700; padding:13px 28px; transition:background .15s; }
    .co-btn:hover { background:var(--red-dark); color:#fff; }
    .co-btn:focus-visible, .co-edit:focus-visible { outline:3px solid rgba(232,28,46,.4); outline-offset:2px; }
    .co-pay { display:flex; align-items:center; gap:14px; padding:15px 16px; border:1.5px solid var(--line); border-radius:8px; margin-bottom:10px; cursor:pointer; transition:border-color .15s, background .15s; }
    .co-pay:hover { border-color:#c9ccd3; }
    .co-pay input { accent-color:var(--red); width:18px; height:18px; flex:none; margin:0; }
    .co-pay:has(input:checked) { border-color:var(--red); background:#fff6f7; }
    .co-pay strong { display:block; font-size:15px; color:var(--ink); }
    .co-pay small { color:var(--muted); font-size:12.5px; }
    .co-pay i.ico { font-size:20px; width:26px; text-align:center; color:var(--ink); }
    .co-card-box { background:#fcfdfe; border:1.5px solid #e2e8f0; border-radius:10px; padding:18px 20px; margin-top:12px; margin-bottom:16px; }
    .co-card-logos { display:flex; gap:8px; align-items:center; }
    .co-card-logos i { font-size:24px; }
    .anet-shield { display:inline-flex; align-items:center; gap:8px; font-size:12.5px; font-weight:600; color:#0f5132; background:#e8f5e9; padding:6px 12px; border-radius:6px; border:1px solid #c8e6c9; }
    .co-side { position:sticky; top:100px; }
    .co-side-head { padding:18px 22px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:baseline; }
    .co-side-head h5 { margin:0; font-size:17px; font-weight:700; color:var(--ink); }
    .co-items { max-height:250px; overflow-y:auto; padding:6px 22px; }
    .co-item { display:flex; gap:12px; align-items:center; padding:12px 0; border-bottom:1px solid var(--line); }
    .co-item:last-child { border-bottom:0; }
    .co-thumb { position:relative; flex:none; }
    .co-thumb img { width:56px; height:56px; object-fit:cover; border-radius:6px; border:1px solid var(--line); }
    .co-thumb span { position:absolute; top:-7px; right:-7px; background:var(--ink); color:#fff; font-size:11px; font-weight:700; min-width:20px; height:20px; border-radius:10px; display:grid; place-items:center; padding:0 5px; }
    .co-item h6 { font-size:13.5px; line-height:1.3; margin:0; color:var(--ink); font-weight:600; }
    .co-totals { padding:16px 22px 22px; background:#fcfcfd; border-top:1px solid var(--line); border-radius:0 0 10px 10px; }
    .co-row { display:flex; justify-content:space-between; font-size:14px; padding:4px 0; color:var(--muted); }
    .co-row b { color:var(--ink); font-weight:600; }
    .co-total { display:flex; justify-content:space-between; align-items:baseline; border-top:1px solid var(--line); margin-top:10px; padding-top:14px; }
    .co-total span { font-weight:700; color:var(--ink); }
    .co-total strong { font-size:26px; color:var(--red); }
    .co-trust { padding:8px 0; }
    .co-trust-item { display:flex; gap:14px; align-items:center; padding:12px 22px; }
    .co-trust-ico { flex:none; width:40px; height:40px; border-radius:10px; background:#fdecee; color:var(--red); display:grid; place-items:center; }
    .co-trust-ico svg { width:22px; height:22px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
    .co-trust-txt { display:block; line-height:1.35; }
    .co-trust-txt strong { display:block; font-size:14px; color:var(--ink); font-weight:700; }
    .co-trust-txt span { display:block; font-size:13px; color:var(--muted); }
    @media (max-width:991px){ .co-side{ position:static; margin-bottom:20px; } .co-page{ padding:28px 0 48px; } }
    @media (prefers-reduced-motion:reduce){ .co * { transition:none !important; } }
</style>

<main class="co">
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="breadcrumb-title">
                <h2>Secure Checkout</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="co-page">
        <div class="container">
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger mb-4" role="alert">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" novalidate>
                @csrf
                <div class="row g-4">

                    <!-- ============ LEFT: STEPS ============ -->
                    <div class="col-lg-7 order-2 order-lg-1">

                        <!-- STEP 1 -->
                        <div class="co-box co-step is-active" data-step="1">
                            <div class="co-head">
                                <span class="co-num"><span class="n">1</span><i class="fas fa-check d-none"></i></span>
                                <div class="flex-grow-1">
                                    <h5 class="co-title">Shipping address</h5>
                                    <div class="co-summary-line" id="sum1"></div>
                                </div>
                                <button type="button" class="co-edit" data-edit="1">Edit</button>
                            </div>
                            <div class="co-body">
                                <p class="co-hint mt-3 mb-3">We ship within the contiguous U.S. Fields marked <span class="text-danger">*</span> are required.</p>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label" for="customer_name">Full name <span class="text-danger">*</span></label>
                                        <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Johnathan Smith" autocomplete="name" required value="{{ old('customer_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="customer_email">Email address <span class="text-danger">*</span></label>
                                        <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="john@example.com" autocomplete="email" required value="{{ old('customer_email') }}">
                                        <span class="co-hint">Your order confirmation is sent here.</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="customer_phone">Phone number <span class="text-danger">*</span></label>
                                        <input type="tel" id="customer_phone" name="customer_phone" class="form-control" placeholder="+1 (555) 019-2834" autocomplete="tel" required value="{{ old('customer_phone') }}">
                                        <span class="co-hint">Used by the freight driver to schedule delivery.</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="shipping_address">Street address <span class="text-danger">*</span></label>
                                        <input type="text" id="shipping_address" name="shipping_address" class="form-control" placeholder="1234 Main Street, Suite / Apt" autocomplete="street-address" required value="{{ old('shipping_address') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" id="city" name="city" class="form-control" placeholder="Dallas" autocomplete="address-level2" required value="{{ old('city') }}">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="form-label" for="state">State <span class="text-danger">*</span></label>
                                        <input type="text" id="state" name="state" class="form-control text-uppercase" placeholder="TX" maxlength="2" autocomplete="address-level1" required value="{{ old('state') }}">
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <label class="form-label" for="zip_code">ZIP code <span class="text-danger">*</span></label>
                                        <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="75001" inputmode="numeric" autocomplete="postal-code" required value="{{ old('zip_code') }}">
                                    </div>
                                </div>
                                <button type="button" class="co-btn mt-4" data-next="2">Save and continue</button>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div class="co-box co-step" data-step="2">
                            <div class="co-head">
                                <span class="co-num"><span class="n">2</span><i class="fas fa-check d-none"></i></span>
                                <div class="flex-grow-1">
                                    <h5 class="co-title">Vehicle &amp; delivery notes</h5>
                                    <div class="co-summary-line" id="sum2"></div>
                                </div>
                                <button type="button" class="co-edit" data-edit="2">Edit</button>
                            </div>
                            <div class="co-body">
                                <div class="row g-3 mt-1">
                                    <div class="col-12">
                                        <label class="form-label" for="vehicle_details">Vehicle year, make &amp; model</label>
                                        <input type="text" id="vehicle_details" name="vehicle_details" class="form-control" placeholder="2018 Ford F-150 XLT 3.5L V6" value="{{ old('vehicle_details') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="vin_number">17-character VIN</label>
                                        <input type="text" id="vin_number" name="vin_number" class="form-control text-uppercase font-monospace" placeholder="1FA6P8CF9H5123456" maxlength="17" value="{{ old('vin_number') }}">
                                        <span class="co-hint" style="color:var(--ok)"><i class="fas fa-check-circle"></i> Our ASE technicians check fitment against your VIN before dispatch.</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="order_notes">Delivery notes (optional)</label>
                                        <textarea id="order_notes" name="order_notes" class="form-control" rows="2" placeholder="Residential delivery with liftgate, or ship to a repair shop">{{ old('order_notes') }}</textarea>
                                    </div>
                                </div>
                                <button type="button" class="co-btn mt-4" data-next="3">Save and continue</button>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div class="co-box co-step" data-step="3">
                            <div class="co-head">
                                <span class="co-num"><span class="n">3</span><i class="fas fa-check d-none"></i></span>
                                <h5 class="co-title">Payment (USD)</h5>
                            </div>
                            <div class="co-body">
                                <div class="mt-3">
                                    <label class="co-pay" for="pay_card">
                                        <input type="radio" name="payment_method" id="pay_card" value="CREDIT_CARD" checked>
                                        <i class="far fa-credit-card ico"></i>
                                        <div class="flex-grow-1">
                                            <strong>Credit or debit card</strong>
                                            <small>Visa, MasterCard, American Express, Discover</small>
                                        </div>
                                        <div class="co-card-logos d-none d-sm-flex">
                                            <i class="fab fa-cc-visa" style="color:#1a1f71"></i>
                                            <i class="fab fa-cc-mastercard" style="color:#eb001b"></i>
                                            <i class="fab fa-cc-amex" style="color:#006fcf"></i>
                                            <i class="fab fa-cc-discover" style="color:#ff6000"></i>
                                        </div>
                                    </label>

                                    <!-- Authorize.Net Card Input Section -->
                                    <div id="cardDetailsBox" class="co-card-box">
                                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                            <span class="anet-shield">
                                                <i class="fas fa-shield-alt text-success"></i> Authorize.Net &bull; 256-Bit SSL Encrypted
                                            </span>
                                            <span class="small text-muted"><i class="fas fa-lock me-1"></i> PCI-DSS Compliant</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label" for="card_name">Name on Card <span class="text-danger">*</span></label>
                                                <input type="text" id="card_name" name="card_name" class="form-control" placeholder="John Doe" value="{{ old('card_name') }}">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label" for="card_number">Card Number <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0"><i class="far fa-credit-card text-muted" id="cardBrandIcon"></i></span>
                                                    <input type="text" id="card_number" name="card_number" class="form-control border-start-0 font-monospace" placeholder="4000 1234 5678 9010" maxlength="19" autocomplete="cc-number" inputmode="numeric" value="{{ old('card_number') }}">
                                                </div>
                                            </div>

                                            <div class="col-7">
                                                <label class="form-label" for="card_expiry">Expiration Date <span class="text-danger">*</span></label>
                                                <input type="text" id="card_expiry" name="card_expiry" class="form-control text-center font-monospace" placeholder="MM / YY" maxlength="7" autocomplete="cc-exp" inputmode="numeric" value="{{ old('card_expiry') }}">
                                            </div>

                                            <div class="col-5">
                                                <label class="form-label" for="card_cvv">Security (CVV) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" id="card_cvv" name="card_cvv" class="form-control text-center font-monospace" placeholder="CVC" maxlength="4" autocomplete="cc-csc" inputmode="numeric" value="{{ old('card_cvv') }}">
                                                    <span class="input-group-text bg-white" title="3 digits on back (or 4 on front for Amex)"><i class="fas fa-question-circle text-muted" style="font-size:12px;"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-muted small">
                                            <i class="fas fa-info-circle me-1 text-primary"></i> Your payment is processed securely via <strong>Authorize.Net</strong>. Card numbers are never stored on our servers.
                                        </div>
                                    </div>

                                    <label class="co-pay" for="pay_paypal">
                                        <input type="radio" name="payment_method" id="pay_paypal" value="PAYPAL">
                                        <i class="fab fa-paypal ico"></i>
                                        <span><strong>PayPal</strong><small>Pay with your PayPal balance or linked account</small></span>
                                    </label>
                                    <label class="co-pay" for="pay_cod">
                                        <input type="radio" name="payment_method" id="pay_cod" value="CASH_ON_DELIVERY">
                                        <i class="fas fa-building-columns ico"></i>
                                        <span><strong>Wire transfer / cash on delivery</strong><small>Commercial accounts only</small></span>
                                    </label>
                                </div>

                                <button type="submit" id="btnPlaceOrder" class="co-btn w-100 mt-4 py-3">
                                    <i class="fas fa-lock me-2"></i> Place order &mdash; ${{ number_format($cartData['total'], 2) }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============ RIGHT: SUMMARY ============ -->
                    <div class="col-lg-5 order-1 order-lg-2">
                        <div class="co-side">
                            <div class="co-box">
                                <div class="co-side-head">
                                    <h5>Order summary</h5>
                                    <a href="/cart" class="small text-decoration-none" style="color:var(--red)">{{ $cartData['count'] }} {{ \Illuminate\Support\Str::plural('item', $cartData['count']) }} &middot; Edit cart</a>
                                </div>

                                <div class="co-items">
                                    @foreach($cartData['items'] as $item)
                                        <div class="co-item">
                                            <div class="co-thumb">
                                                <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}">
                                                <span>{{ $item['quantity'] }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>{{ $item['name'] }}</h6>
                                                <small class="text-muted">${{ number_format($item['price'], 2) }} each</small>
                                            </div>
                                            <strong>${{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="co-totals">
                                    <div class="co-row"><span>Parts subtotal</span><b>${{ number_format($cartData['subtotal'], 2) }}</b></div>
                                    @if($cartData['discount'] > 0)
                                        <div class="co-row" style="color:var(--ok)"><span>Coupon ({{ $cartData['coupon']['code'] }})</span><b style="color:var(--ok)">-${{ number_format($cartData['discount'], 2) }}</b></div>
                                    @endif
                                    <div class="co-row"><span>Freight delivery</span><b style="color:var(--ok)">Free liftgate</b></div>
                                    <div class="co-row"><span>Estimated tax (7%)</span><b>${{ number_format($cartData['tax'], 2) }}</b></div>
                                    <div class="co-total"><span>Total due</span><strong>${{ number_format($cartData['total'], 2) }}</strong></div>
                                    <div class="text-center small text-muted mt-3"><i class="fas fa-lock text-success me-1"></i> Your information is encrypted and secure</div>
                                </div>
                            </div>

                            <div class="co-box mt-3 d-none d-lg-block">
                                <div class="co-trust">
                                    <div class="co-trust-item">
                                        <div class="co-trust-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16 9.5"/></svg></div>
                                        <div class="co-trust-txt"><strong>Guaranteed to fit</strong><span>VIN-checked by ASE technicians</span></div>
                                    </div>
                                    <div class="co-trust-item">
                                        <div class="co-trust-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 6h11v10H2z"/><path d="M13 9h4.5l3.5 3.5V16h-8z"/><circle cx="6.5" cy="17.5" r="1.8"/><circle cx="17.5" cy="17.5" r="1.8"/></svg></div>
                                        <div class="co-trust-txt"><strong>Fast freight shipping</strong><span>Liftgate delivery included</span></div>
                                    </div>
                                    <div class="co-trust-item">
                                        <div class="co-trust-ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7.5 3v5.5c0 4.5-3.2 8-7.5 9.5-4.3-1.5-7.5-5-7.5-9.5V6z"/><path d="M9 12l2.2 2.2L15.2 10"/></svg></div>
                                        <div class="co-trust-txt"><strong>1-year replacement warranty</strong><span>On every part we sell</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>
</main>

<script>
(function () {
    var form  = document.getElementById('checkoutForm');
    var steps = form.querySelectorAll('.co-step');
    var cardBox = document.getElementById('cardDetailsBox');
    var payRadios = form.querySelectorAll('input[name="payment_method"]');
    var cardNumInput = document.getElementById('card_number');
    var cardExpInput = document.getElementById('card_expiry');
    var cardCvvInput = document.getElementById('card_cvv');
    var cardNameInput = document.getElementById('card_name');
    var cardBrandIcon = document.getElementById('cardBrandIcon');
    var btnPlaceOrder = document.getElementById('btnPlaceOrder');

    function open(n) {
        steps.forEach(function (s) {
            var i = +s.dataset.step;
            s.classList.toggle('is-active', i === n);
            if (i < n) s.classList.add('is-done'); else s.classList.remove('is-done');
            var num = s.querySelector('.co-num');
            num.querySelector('.n').classList.toggle('d-none', i < n);
            num.querySelector('.fa-check').classList.toggle('d-none', !(i < n));
        });
        var f = form.querySelector('.co-step[data-step="' + n + '"] input, .co-step[data-step="' + n + '"] textarea');
        if (f && n < 3) f.focus({ preventScroll: true });
        form.querySelector('.co-step[data-step="' + n + '"]').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function valid(n) {
        var fields = form.querySelectorAll('.co-step[data-step="' + n + '"] [required]');
        for (var i = 0; i < fields.length; i++) {
            if (!fields[i].checkValidity()) { fields[i].reportValidity(); return false; }
        }
        return true;
    }

    function v(id) { 
        var el = document.getElementById(id);
        return el ? (el.value || '').trim() : ''; 
    }

    function fillSummaries() {
        document.getElementById('sum1').textContent =
            v('customer_name') + ' · ' + v('shipping_address') + ', ' + v('city') + ', ' + v('state').toUpperCase() + ' ' + v('zip_code');
        document.getElementById('sum2').textContent = v('vehicle_details') || v('vin_number') || 'No vehicle details added';

        // Pre-fill card name if empty
        if (cardNameInput && !cardNameInput.value) {
            cardNameInput.value = v('customer_name');
        }
    }

    // Payment method switcher
    function syncPaymentMethod() {
        var isCard = document.getElementById('pay_card').checked;
        if (cardBox) {
            cardBox.style.display = isCard ? 'block' : 'none';
        }
        if (cardNumInput) cardNumInput.required = isCard;
        if (cardExpInput) cardExpInput.required = isCard;
        if (cardCvvInput) cardCvvInput.required = isCard;
    }

    payRadios.forEach(function (radio) {
        radio.addEventListener('change', syncPaymentMethod);
    });
    syncPaymentMethod();

    // Format Card Number & detect brand
    if (cardNumInput) {
        cardNumInput.addEventListener('input', function (e) {
            var val = this.value.replace(/\D/g, '').substring(0, 16);
            var parts = [];
            for (var i = 0; i < val.length; i += 4) {
                parts.push(val.substring(i, i + 4));
            }
            this.value = parts.join(' ');

            // Card Brand Detection
            if (cardBrandIcon) {
                cardBrandIcon.className = 'text-muted';
                if (/^4/.test(val)) {
                    cardBrandIcon.className = 'fab fa-cc-visa text-primary';
                } else if (/^(5[1-5]|2[2-7])/.test(val)) {
                    cardBrandIcon.className = 'fab fa-cc-mastercard text-danger';
                } else if (/^3[47]/.test(val)) {
                    cardBrandIcon.className = 'fab fa-cc-amex text-info';
                } else if (/^(6011|65)/.test(val)) {
                    cardBrandIcon.className = 'fab fa-cc-discover text-warning';
                } else {
                    cardBrandIcon.className = 'far fa-credit-card text-muted';
                }
            }
        });
    }

    // Format Expiration Date (MM / YY)
    if (cardExpInput) {
        cardExpInput.addEventListener('input', function () {
            var val = this.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 3) {
                this.value = val.substring(0, 2) + ' / ' + val.substring(2);
            } else {
                this.value = val;
            }
        });
    }

    // Format CVV
    if (cardCvvInput) {
        cardCvvInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').substring(0, 4);
        });
    }

    form.querySelectorAll('[data-next]').forEach(function (b) {
        b.addEventListener('click', function () {
            var next = +b.dataset.next;
            if (!valid(next - 1)) return;
            fillSummaries();
            open(next);
        });
    });

    form.querySelectorAll('[data-edit]').forEach(function (b) {
        b.addEventListener('click', function () { open(+b.dataset.edit); });
    });

    // Guard on submission
    form.addEventListener('submit', function (e) {
        if (!valid(1)) {
            e.preventDefault();
            open(1);
            return;
        }

        var isCard = document.getElementById('pay_card').checked;
        if (isCard) {
            var numClean = (cardNumInput.value || '').replace(/\D/g, '');
            if (numClean.length < 13) {
                e.preventDefault();
                open(3);
                alert('Please enter a valid card number.');
                cardNumInput.focus();
                return;
            }
            var expClean = (cardExpInput.value || '').replace(/\D/g, '');
            if (expClean.length < 4) {
                e.preventDefault();
                open(3);
                alert('Please enter a valid card expiration date (MM / YY).');
                cardExpInput.focus();
                return;
            }
            var cvvClean = (cardCvvInput.value || '').trim();
            if (cvvClean.length < 3) {
                e.preventDefault();
                open(3);
                alert('Please enter a valid 3 or 4 digit CVV/CVC code.');
                cardCvvInput.focus();
                return;
            }
        }

        // Processing state
        if (btnPlaceOrder) {
            btnPlaceOrder.disabled = true;
            btnPlaceOrder.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Securely Processing via Authorize.Net...';
        }
    });

    // If server returned validation errors, open step 1 or 3 depending on error
    @if (isset($errors) && $errors->any())
        @if ($errors->has('card_number') || $errors->has('card_expiry') || $errors->has('card_cvv'))
            open(3);
        @else
            open(1);
        @endif
    @endif
})();
</script>
@endsection