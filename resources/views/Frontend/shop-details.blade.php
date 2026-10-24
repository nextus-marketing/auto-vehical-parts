@extends('layouts.frontend')
@section('title') {{ $product->name }} | Auto Vehicle Parts @endsection
@section('content')

<style>
    /* ===== Product page (scoped with .pd-) — palette: navy #0c2957, red #e81c2e, green #28a745 ===== */
    .pd-section { padding: 60px 0 40px; background: #fff; }
    .pd-section *, .pd-tabs-area * { box-sizing: border-box; }

    /* Gallery */
    .pd-gallery-main {
        background: #fff; border: 1px solid #e3e7ee; border-radius: 10px;
        padding: 24px; display: flex; align-items: center; justify-content: center;
        min-height: 440px;
    }
    .pd-gallery-main img { max-height: 420px; width: 100%; object-fit: contain; }
    .pd-thumbs { display: flex; gap: 10px; margin-top: 14px; overflow-x: auto; padding-bottom: 4px; }
    .pd-thumbs img {
        width: 76px; height: 76px; object-fit: cover; cursor: pointer; flex: 0 0 auto;
        border: 1px solid #e3e7ee; border-radius: 8px; padding: 3px; background: #fff;
        transition: border-color .15s;
    }
    .pd-thumbs img:hover { border-color: #e81c2e; }

    /* Info panel */
    .pd-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .pd-badge { display: inline-block; font-size: 12px; font-weight: 600; padding: 5px 10px; border-radius: 4px; line-height: 1; }
    .pd-badge-red { background: #e81c2e; color: #fff; }
    .pd-badge-navy { background: #0c2957; color: #fff; }

    .pd-title { font-size: 30px; line-height: 1.25; font-weight: 700; color: #0c2957; margin: 0 0 18px; }

    .pd-price-box {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px 14px;
        padding: 18px 0; border-top: 1px solid #e3e7ee; border-bottom: 1px solid #e3e7ee;
    }
    .pd-price { font-size: 36px; font-weight: 700; color: #0c2957; line-height: 1; }
    .pd-old-price { font-size: 18px; color: #8a93a3; text-decoration: line-through; }
    .pd-save { background: #28a745; color: #fff; font-size: 12px; font-weight: 700; padding: 5px 10px; border-radius: 4px; }
    .pd-stock {
        margin-left: auto; display: inline-flex; align-items: center; gap: 7px;
        font-size: 13px; font-weight: 600; color: #1e7e34;
        background: #eaf6ed; border: 1px solid #bfe3c8; padding: 6px 12px; border-radius: 999px;
    }
    .pd-stock::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #28a745; }
    .pd-stock.low { color: #b4232f; background: #fdecee; border-color: #f6c3c8; }
    .pd-stock.low::before { background: #e81c2e; }

    .pd-short { font-size: 15px; line-height: 1.7; color: #55607a; margin: 20px 0; }

    /* Trust / spec list */
    .pd-specs {
        list-style: none; margin: 0 0 20px; padding: 0;
        border: 1px solid #e3e7ee; border-radius: 10px; overflow: hidden;
    }
    .pd-specs li { display: flex; gap: 12px; padding: 12px 16px; font-size: 14px; line-height: 1.5; color: #55607a; align-items: flex-start; }
    .pd-specs li + li { border-top: 1px solid #eef1f6; }
    .pd-specs i { color: #28a745; margin-top: 3px; font-size: 15px; }
    .pd-specs strong { color: #0c2957; font-weight: 600; margin-right: 4px; }

    .pd-cat { font-size: 14px; color: #55607a; margin-bottom: 22px; }
    .pd-cat strong { color: #0c2957; font-weight: 600; }
    .pd-cat a { color: #e81c2e; font-weight: 600; text-decoration: none; }
    .pd-cat a:hover { text-decoration: underline; }

    /* Actions */
    .pd-actions { display: flex; align-items: stretch; flex-wrap: wrap; gap: 12px; }
    .pd-qty { display: inline-flex; border: 1px solid #cfd6e2; border-radius: 8px; overflow: hidden; height: 50px; background: #fff; }
    .pd-qty button {
        width: 44px; border: 0; background: #f3f5f9; color: #0c2957; font-size: 20px; font-weight: 600;
        cursor: pointer; line-height: 1; padding: 0; transition: background .15s, color .15s;
    }
    .pd-qty button:hover { background: #e81c2e; color: #fff; }
    .pd-qty input {
        width: 54px; border: 0; border-left: 1px solid #cfd6e2; border-right: 1px solid #cfd6e2;
        text-align: center; font-size: 16px; font-weight: 600; color: #0c2957; background: #fff;
        -moz-appearance: textfield; outline: none; padding: 0;
    }
    .pd-qty input::-webkit-outer-spin-button, .pd-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .pd-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 50px; padding: 0 26px; border-radius: 8px; border: 0;
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer; line-height: 1;
        transition: background .15s, transform .15s;
    }
    .pd-btn:focus-visible, .pd-qty button:focus-visible, .pd-tab:focus-visible { outline: 3px solid rgba(12,41,87,.35); outline-offset: 2px; }
    .pd-btn-red { background: #e81c2e; color: #fff; }
    .pd-btn-red:hover { background: #c9162a; color: #fff; }
    .pd-btn-navy { background: #0c2957; color: #fff; }
    .pd-btn-navy:hover { background: #081c3e; color: #fff; }
    .pd-btn:active { transform: translateY(1px); }

    /* Help strip */
    .pd-help {
        display: flex; align-items: center; gap: 14px; margin-top: 22px;
        padding: 14px 16px; border: 1px solid #e3e7ee; border-left: 4px solid #e81c2e;
        border-radius: 8px; background: #f8f9fc;
    }
    .pd-help i { color: #e81c2e; font-size: 18px; }
    .pd-help span { font-size: 14px; color: #55607a; }
    .pd-help a { color: #0c2957; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .pd-help a:hover { color: #e81c2e; }

    /* Share */
    .pd-share { display: flex; align-items: center; gap: 10px; margin-top: 22px; padding-top: 18px; border-top: 1px solid #e3e7ee; }
    .pd-share > span { font-size: 13px; color: #6b768c; margin-right: 4px; }
    .pd-share a {
        width: 36px; height: 36px; border-radius: 50%; border: 1px solid #dbe1ec; color: #0c2957;
        display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 14px;
        transition: all .15s;
    }
    .pd-share a:hover { background: #0c2957; border-color: #0c2957; color: #fff; }

    /* Tabs */
    .pd-tabs-area { padding: 30px 0 70px; background: #f8f9fc; border-top: 1px solid #e3e7ee; }
    .pd-tabs { display: flex; flex-wrap: wrap; gap: 4px; list-style: none; margin: 0 0 26px; padding: 0; border-bottom: 2px solid #e3e7ee; }
    .pd-tabs .nav-item { margin: 0; }
    .pd-tab {
        display: block; padding: 14px 22px; margin-bottom: -2px; font-size: 15px; font-weight: 600;
        color: #55607a !important; text-decoration: none; border-bottom: 3px solid transparent;
        background: transparent; transition: color .15s, border-color .15s;
    }
    .pd-tab:hover { color: #0c2957 !important; }
    .pd-tab.active { color: #e81c2e !important; border-bottom-color: #e81c2e; background: transparent; }

    .pd-panel { background: #fff; border: 1px solid #e3e7ee; border-radius: 10px; padding: 30px; }
    .pd-panel h4 { color: #0c2957; font-weight: 700; margin-bottom: 12px; }
    .pd-panel p { color: #55607a; line-height: 1.75; }

    .pd-table { width: 100%; border-collapse: collapse; margin-top: 18px; font-size: 14px; }
    .pd-table th { background: #0c2957; color: #fff; text-align: left; padding: 12px 16px; font-weight: 600; }
    .pd-table td { padding: 12px 16px; border-bottom: 1px solid #e3e7ee; color: #55607a; }
    .pd-table td:first-child { width: 32%; color: #0c2957; font-weight: 600; background: #f8f9fc; }
    .pd-table code { color: #e81c2e; background: #fdecee; padding: 2px 7px; border-radius: 4px; }

    /* Reviews */
    .pd-rating { display: flex; align-items: center; gap: 28px; padding-bottom: 24px; flex-wrap: wrap; }
    .pd-rating-score { text-align: center; min-width: 140px; }
    .pd-rating-score h2 { font-size: 52px; font-weight: 700; color: #0c2957; margin: 0; line-height: 1; }
    .pd-stars { color: #f5a623; font-size: 14px; letter-spacing: 2px; margin: 6px 0; }
    .pd-rating-score small { color: #6b768c; }
    .pd-rating-text { flex: 1; min-width: 240px; padding-left: 28px; border-left: 1px solid #e3e7ee; }
    .pd-rating-text p { margin-bottom: 6px; }
    .pd-review { border-top: 1px solid #e3e7ee; padding-top: 18px; margin-top: 4px; }
    .pd-review-head { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .pd-review-head strong { color: #0c2957; }
    .pd-verified { font-size: 12px; font-weight: 600; color: #1e7e34; background: #eaf6ed; padding: 3px 10px; border-radius: 999px; }

    /* Related */
    .pd-related { padding: 60px 0 70px; background: #fff; }
    .pd-related h3 { color: #0c2957; font-weight: 700; text-align: center; margin-bottom: 32px; }
    .pd-card { height: 100%; border: 1px solid #e3e7ee; border-radius: 10px; background: #fff; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .2s; }
    .pd-card:hover { box-shadow: 0 8px 24px rgba(12,41,87,.10); }
    .pd-card img { width: 100%; height: 180px; object-fit: cover; display: block; }
    .pd-card-body { padding: 14px 16px 16px; display: flex; flex-direction: column; flex: 1; }
    .pd-card-body h6 { font-size: 14px; line-height: 1.4; margin: 0 0 12px; }
    .pd-card-body h6 a { color: #0c2957; text-decoration: none; font-weight: 600; }
    .pd-card-foot { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .pd-card-price { font-weight: 700; color: #e81c2e; font-size: 16px; }
    .pd-card-btn { border: 1px solid #e81c2e; color: #e81c2e; background: #fff; border-radius: 6px; padding: 7px 12px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; }
    .pd-card-btn:hover { background: #e81c2e; color: #fff; }

    @media (max-width: 767px) {
        .pd-title { font-size: 24px; }
        .pd-price { font-size: 30px; }
        .pd-stock { margin-left: 0; }
        .pd-gallery-main { min-height: 280px; }
        .pd-btn { flex: 1 1 100%; }
        .pd-tab { padding: 12px 14px; font-size: 14px; }
        .pd-panel { padding: 20px; }
        .pd-rating-text { padding-left: 0; border-left: 0; }
    }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>

<main>
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>{{ $product->name }}</h2>
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item"><a href="/shop">Shop Auto Parts</a></li>
                                        @if($product->category)
                                            <li class="breadcrumb-item"><a href="/shop?category={{ $product->category->slug }}">{{ $product->category->name }}</a></li>
                                        @endif
                                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
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

    <!-- product top section -->
    <section class="pd-section">
        <div class="container">
            <div class="row g-5">

                <!-- Gallery -->
                <div class="col-xl-6 col-lg-6">
                    <div class="pd-gallery-main">
                        <img src="{{ $product->photo_url }}" id="mainLargeImg" alt="{{ $product->name }}">
                    </div>

                    @php
                        $gallery = is_array($product->other_photo) ? $product->other_photo : [];
                        if (empty($gallery) && $product->photo) {
                            $gallery = [$product->photo];
                        }
                    @endphp
                    @if(count($gallery) > 1)
                        <div class="pd-thumbs">
                            @foreach($gallery as $idx => $gImg)
                                @php $thumbUrl = Str::startsWith($gImg, '/') ? $gImg : '/storage/' . $gImg; @endphp
                                <img src="{{ $thumbUrl }}" alt="Angle {{ $idx + 1 }}"
                                     onclick="$('#mainLargeImg').attr('src', '{{ $thumbUrl }}');">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="col-xl-6 col-lg-6">
                    <div class="pd-badges">
                        <span class="pd-badge pd-badge-red">Certified OEM Recycled Part</span>
                        @if($product->part_number)
                            <span class="pd-badge pd-badge-navy">Part # {{ $product->part_number }}</span>
                        @endif
                    </div>

                    <h1 class="pd-title">{{ $product->name }}</h1>

                    <div class="pd-price-box">
                        <span class="pd-price">${{ number_format($product->price, 2) }}</span>
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="pd-old-price">${{ number_format($product->compare_price, 2) }}</span>
                            <span class="pd-save">
                                Save ${{ number_format($product->compare_price - $product->price, 2) }}
                                ({{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% off)
                            </span>
                        @endif
                        <span class="pd-stock {{ $product->stock_status == 'IN_STOCK' ? '' : 'low' }}">
                            {{ $product->stock_status == 'IN_STOCK' ? 'In stock, ready to freight' : 'Low stock' }}
                        </span>
                    </div>

                    <p class="pd-short">
                        {{ $product->short_description ?: 'Authentic factory OEM certified part carefully inspected, tested, and stored in our climate-controlled warehouse. Ready for rapid dispatch with free nationwide freight.' }}
                    </p>

                    <ul class="pd-specs">
                        <li><i class="fas fa-check-circle"></i><div><strong>Mileage / Condition:</strong> {{ $product->mileage ?: 'Verified Low Miles Donor Vehicle' }}</div></li>
                        <li><i class="fas fa-check-circle"></i><div><strong>Warranty Terms:</strong> {{ $product->warranty ?: '1-Year Comprehensive Replacement Guarantee' }}</div></li>
                        <li><i class="fas fa-check-circle"></i><div><strong>Fitment Guarantee:</strong> {{ $product->fitment ?: '100% Guaranteed Exact Fit by VIN Verification' }}</div></li>
                        <li><i class="fas fa-check-circle"></i><div><strong>Nationwide Shipping:</strong> Free Freight with Liftgate Delivery (3-5 Business Days across USA)</div></li>
                    </ul>

                    @if($product->category)
                        <div class="pd-cat">
                            <strong>Category:</strong>
                            <a href="/shop?category={{ $product->category->slug }}">{{ $product->category->name }}</a>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="pd-actions">
                        <div class="pd-qty">
                            <button type="button" aria-label="Decrease quantity"
                                    onclick="var v = parseInt($('#itemQty').val()) || 1; if(v>1) $('#itemQty').val(v-1);">&minus;</button>
                            <input type="number" id="itemQty" value="1" min="1" max="10" aria-label="Quantity">
                            <button type="button" aria-label="Increase quantity"
                                    onclick="var v = parseInt($('#itemQty').val()) || 1; if(v<10) $('#itemQty').val(v+1);">+</button>
                        </div>

                        <button type="button" class="pd-btn pd-btn-red"
                                onclick="addToCartGlobal({{ $product->id }}, parseInt($('#itemQty').val()))">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>

                        <a href="/checkout" class="pd-btn pd-btn-navy"
                           onclick="addToCartGlobal({{ $product->id }}, parseInt($('#itemQty').val()))">
                            Buy Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="pd-help">
                        <i class="fas fa-phone-alt"></i>
                        <span>Questions on fitment? Call our ASE tech team:
                            <a href="tel:+18883709806">+1-888-370-9806</a>
                        </span>
                    </div>

                    <div class="pd-share">
                        <span>Share part</span>
                        <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Share on Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- tabs -->
    <section class="pd-tabs-area">
        <div class="container">
            <ul class="nav pd-tabs" id="descTab" role="tablist">
                <li class="nav-item">
                    <a class="pd-tab active" id="desc-tab" data-bs-toggle="tab" href="#tab-desc" role="tab" aria-selected="true">Description &amp; Inspection</a>
                </li>
                <li class="nav-item">
                    <a class="pd-tab" id="fitment-tab" data-bs-toggle="tab" href="#tab-fitment" role="tab" aria-selected="false">Compatibility &amp; VIN Matching</a>
                </li>
                <li class="nav-item">
                    <a class="pd-tab" id="reviews-tab" data-bs-toggle="tab" href="#tab-reviews" role="tab" aria-selected="false">Customer Reviews ({{ $product->reviews_count ?: 12 }})</a>
                </li>
            </ul>

            <div class="tab-content" id="descTabContent">
                <!-- Description -->
                <div class="tab-pane fade show active" id="tab-desc" role="tabpanel">
                    <div class="pd-panel event-text">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- Compatibility -->
                <div class="tab-pane fade" id="tab-fitment" role="tabpanel">
                    <div class="pd-panel">
                        <h4>Guaranteed exact fit by vehicle VIN</h4>
                        <p>To ensure 100% plug-and-play fitment, our technical specialists run every order through our nationwide OEM interchange database before freight shipment.</p>

                        <div class="table-responsive">
                            <table class="pd-table">
                                <thead>
                                    <tr>
                                        <th>Specification</th>
                                        <th>Donor vehicle &amp; component verification</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Part Condition</td><td>Certified Pre-Owned OEM Recycled Component</td></tr>
                                    <tr><td>Part / Casting Number</td><td><code>{{ $product->part_number ?: 'OEM-STANDARD' }}</code></td></tr>
                                    <tr><td>Donor Mileage</td><td>{{ $product->mileage ?: 'Verified 54,200 Original Miles' }}</td></tr>
                                    <tr><td>Testing Procedure</td><td>Cold compression tested, borescope cylinder wall inspection, and leak-down test passed.</td></tr>
                                    <tr><td>Warranty Protection</td><td>{{ $product->warranty ?: '1-Year Comprehensive Replacement Guarantee' }}</td></tr>
                                    <tr><td>Freight Shipping</td><td>Commercial liftgate freight included (3-5 business days across mainland USA)</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="tab-pane fade" id="tab-reviews" role="tabpanel">
                    <div class="pd-panel">
                        <div class="pd-rating">
                            <div class="pd-rating-score">
                                <h2>{{ number_format($product->rating ?: 5.0, 1) }}</h2>
                                <div class="pd-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                                <small>Based on {{ $product->reviews_count ?: 12 }} certified buyer reviews</small>
                            </div>
                            <div class="pd-rating-text">
                                <p><strong style="color:#0c2957;">100%</strong> of customers would recommend this OEM part to a colleague or mechanic.</p>
                                <p>All reviews are verified purchases shipped directly from Auto Vehicle Parts dismantler partners.</p>
                            </div>
                        </div>

                        <div class="pd-review">
                            <div class="pd-review-head">
                                <strong>Robert Kowalski &mdash; Austin, TX</strong>
                                <span class="pd-verified">Verified buyer</span>
                            </div>
                            <div class="pd-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="mb-0">Engine arrived securely bolted to a heavy-duty wooden pallet with shrink wrap. Compression test was exactly as advertised. Dropped right into my truck and fired up with zero CEL codes. Saved over $2,000 compared to dealer crate motor!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="pd-related">
            <div class="container">
                <h3>Related OEM Auto Parts</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $rel)
                        <div class="col-lg-3 col-md-6">
                            <div class="pd-card">
                                <a href="/shop/{{ $rel->slug }}">
                                    <img src="{{ $rel->photo_url }}" alt="{{ $rel->name }}">
                                </a>
                                <div class="pd-card-body">
                                    <h6><a href="/shop/{{ $rel->slug }}">{{ Str::limit($rel->name, 45) }}</a></h6>
                                    <div class="pd-card-foot">
                                        <span class="pd-card-price">${{ number_format($rel->price, 2) }}</span>
                                        <button type="button" class="pd-card-btn" onclick="addToCartGlobal({{ $rel->id }}, 1)">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
@endsection