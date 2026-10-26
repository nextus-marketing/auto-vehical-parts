@extends('layouts.frontend')
@section('title') Shop Certified OEM Used Auto Parts | Auto Vehicle Parts @endsection
@section('content')

<main>
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Shop Used OEM Auto Parts</h2>
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Shop Auto Parts</li>
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

    <style>
        .mk {
            --mk-ink: #0f1111;
            --mk-muted: #565959;
            --mk-line: #dde1e6;
            --mk-bg: #f7f8fa;
            --mk-blue: #0b5cab;
            --mk-orange: #e81c2e;
            --mk-deal: #cc0c39;
            --mk-green: #067d62;
            background: var(--mk-bg);
            color: var(--mk-ink);
            padding: 0 0 56px;
        }
        .mk *, .mk *::before, .mk *::after { box-sizing: border-box; }
        .mk-wrap { max-width: 1300px; margin: 0 auto; padding: 0 12px; }

        /* ===== Keystone scrolling ticker ===== */
        .mk-ticker {
            background: #0c2957;
            color: #fff;
            overflow: hidden;
            white-space: nowrap;
            border-bottom: 3px solid #e81c2e;
        }
        .mk-ticker-track {
            display: flex;
            width: max-content;
            animation: mk-scroll 28s linear infinite;
        }
        .mk-ticker:hover .mk-ticker-track { animation-play-state: paused; }
        .mk-ticker-group { display: flex; flex: none; }
        .mk-ticker-group span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 36px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .2px;
        }
        .mk-ticker-group i { color: #ffd48a; }

        @keyframes mk-scroll {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        @media (prefers-reduced-motion: reduce) {
            .mk-ticker-track { animation: none; }
        }
        /* ===================================== */

        .mk-bar {
            background: #fff;
            border-bottom: 1px solid var(--mk-line);
            position: sticky; top: 0; z-index: 20;
        }
        .mk-search-row { display: flex; gap: 0; padding: 12px 0 8px; }
        .mk-select, .mk-input, .mk-go { height: 42px; border: 0; font-size: 14px; }
        .mk-select {
            width: 240px; flex: none;
            padding: 0 10px;
            background: #eef2f6; color: var(--mk-ink);
            border-radius: 8px 0 0 8px;
            border-right: 1px solid var(--mk-line);
        }
        .mk-input {
            flex: 1; min-width: 0;
            padding: 0 14px;
            border-top: 2px solid #0c2957;
            border-bottom: 2px solid #0c2957;
            background: #fff; color: var(--mk-ink);
        }
        .mk-input:focus { outline: none; box-shadow: inset 0 0 0 2px #ffd48a; }
        .mk-go {
            width: 52px; flex: none;
            background: #0c2957; color: #fff;
            border-radius: 0 8px 8px 0; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .mk-go svg { width: 18px; height: 18px; }

        .mk-chips {
            display: flex; gap: 6px; overflow-x: auto; padding: 6px 0 10px;
            scrollbar-width: thin;
        }
        .mk-chip {
            white-space: nowrap; font-size: 13px; font-weight: 500;
            padding: 6px 12px; border-radius: 999px;
            border: 1px solid var(--mk-line); background: #fff; color: var(--mk-ink);
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: all .15s;
        }
        .mk-chip:hover { border-color: var(--mk-ink); color: #000; }
        .mk-chip.is-active { background: #0c2957; color: #fff; border-color: #0c2957; }
        .mk-chip.is-active small { color: rgba(255,255,255,0.8); }

        .mk-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: 16px 0 10px; flex-wrap: wrap; gap: 8px;
        }
        .mk-result { font-size: 14px; color: var(--mk-muted); margin: 0; }
        .mk-sort {
            height: 36px; padding: 0 10px; font-size: 13px;
            border: 1px solid var(--mk-line); border-radius: 6px;
            background: #fff; color: var(--mk-ink);
        }

        .mk-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .mk-card {
            background: #fff;
            border: 1px solid var(--mk-line);
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, border-color .2s;
        }
        .mk-card:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.08); border-color: #bbb; }

        /* ===== Auto-fit product image (any uploaded size/ratio) ===== */
        .mk-thumb {
            position: relative;
            display: flex; align-items: center; justify-content: center;
            aspect-ratio: 4 / 3;
            background: #fff;
            border: 1px solid #f0f1f3;
            border-radius: 6px;
            overflow: hidden;
            padding: 8px;
        }
        .mk-thumb img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;   /* whole image always visible, never cropped */
            display: block;
            transition: transform .3s;
        }
        .mk-card:hover .mk-thumb img { transform: scale(1.04); }
        /* ============================================================ */

        .mk-deal {
            position: absolute; left: 6px; top: 6px;
            background: var(--mk-deal); color: #fff;
            font-size: 11px; font-weight: 700;
            padding: 2px 6px; border-radius: 3px;
            z-index: 2;
        }
        .mk-cat-tag {
            font-size: 11px; font-weight: 600; text-transform: uppercase; color: #e81c2e; margin-top: 6px;
        }
        .mk-info { display: flex; flex-direction: column; flex: 1; padding: 4px 2px 0; }
        .mk-name {
            margin: 2px 0 0; font-size: 14px; font-weight: 600; line-height: 1.35;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            min-height: 2.7em;
        }
        .mk-name a { color: var(--mk-ink); text-decoration: none; }
        .mk-name a:hover { color: var(--mk-blue); text-decoration: underline; }
        .mk-rate { display: flex; align-items: center; gap: 4px; margin: 4px 0; font-size: 12px; color: var(--mk-blue); }
        .mk-stars { color: #f59e0b; letter-spacing: 1px; font-size: 13px; line-height: 1; }
        .mk-stars .off { color: #d5d9de; }
        .mk-price { display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 6px; margin-bottom: 8px; }
        .mk-price b { font-size: 18px; font-weight: 700; color: #0c2957; }
        .mk-price s { font-size: 12px; color: var(--mk-muted); }
        .mk-price span { font-size: 12px; color: var(--mk-green); font-weight: 600; }

        .mk-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-top: auto; }
        .mk-btn {
            height: 36px; display: flex; align-items: center; justify-content: center;
            border-radius: 6px; font-size: 12px; font-weight: 600;
            text-decoration: none; cursor: pointer; border: 1px solid transparent;
            transition: background .15s;
        }
        .mk-btn--cart { background: #e81c2e; border-color: #e81c2e; color: #fff; }
        .mk-btn--cart:hover { background: #c71425; border-color: #c71425; color: #fff; }
        .mk-btn--buy { background: #0c2957; border-color: #0c2957; color: #fff; }
        .mk-btn--buy:hover { background: #071937; border-color: #071937; color: #fff; }

        .mk-empty {
            display: none; text-align: center; padding: 48px 16px;
            background: #fff; border-radius: 8px; color: var(--mk-muted);
            margin-top: 20px;
        }
        .mk-empty.is-visible { display: block; }

        .pagination-wrap {
            margin-top: 35px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1199px) { .mk-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 991px)  { .mk-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 575px) {
            .mk-select { display: none; }
            .mk-input { border-left: 2px solid #0c2957; border-radius: 8px 0 0 8px; }
            .mk-grid { grid-template-columns: 1fr; gap: 10px; }
            .mk-ticker-group span { font-size: 13px; padding: 8px 24px; }
        }
    </style>

    <section class="mk">

        <!-- Keystone scrolling ticker -->
        <div class="mk-ticker" aria-label="Aftermarket Products Sold By Keystone. Tested, inspected and genuine OEM quality, fitment guaranteed.">
            <div class="mk-ticker-track">
                @for ($t = 0; $t < 2; $t++)
                    <div class="mk-ticker-group" @if($t == 1) aria-hidden="true" @endif>
                        <span><i class="fas fa-shield-alt"></i> Aftermarket Products Sold By Keystone</span>
                        <span><i class="fas fa-check-circle"></i> Tested &amp; inspected</span>
                        <span><i class="fas fa-cog"></i> Genuine OEM quality</span>
                        <span><i class="fas fa-car"></i> Fitment guaranteed</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Top bar: category select + search + category chips -->
        <div class="mk-bar">
            <div class="mk-wrap">
                <div class="mk-search-row" role="search">
                    <select class="mk-select" id="mkCatSelect" aria-label="Category">
                        <option value="all">All Auto Parts Categories</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->slug }}" {{ ($categorySlug == $c->slug) ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <input class="mk-input" type="search" id="mkSearch" placeholder="Search by OEM part name, SKU, or vehicle..." aria-label="Search auto parts" value="{{ $search }}">
                    <button class="mk-go" type="button" id="mkGo" aria-label="Search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    </button>
                </div>
                <div class="mk-chips" id="mkChips">
                    <button type="button" class="mk-chip {{ (!$categorySlug || $categorySlug == 'all') ? 'is-active' : '' }}" data-cat="all">
                        All Parts <small>({{ $totalProductsCount }})</small>
                    </button>
                    @foreach ($categories as $c)
                        <button type="button" class="mk-chip {{ ($categorySlug == $c->slug) ? 'is-active' : '' }}" data-cat="{{ $c->slug }}">
                            {{ $c->name }} <small>({{ $c->products_count ?? $c->products()->count() }})</small>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mk-wrap">
            <div class="mk-head">
                <p class="mk-result" id="mkResult" aria-live="polite">
                    Showing <strong>{{ $products->count() }}</strong> verified OEM parts in stock
                </p>
                <select class="mk-sort" id="mkSort" aria-label="Sort products">
                    <option value="default">Featured OEM Parts</option>
                    <option value="price-asc">Price: Low to High ($)</option>
                    <option value="price-desc">Price: High to Low ($)</option>
                    <option value="rating">Top Customer Reviews</option>
                    <option value="name">Name: A to Z</option>
                </select>
            </div>

            <div class="mk-grid" id="mkGrid">
                @forelse ($products as $i => $p)
                    @php
                        $off = ($p->compare_price && $p->compare_price > $p->price) ? round((($p->compare_price - $p->price) / $p->compare_price) * 100) : 0;
                        $full = (int) floor($p->rating ?: 5.0);
                    @endphp
                    <article class="mk-card"
                             data-cat="{{ $p->category ? $p->category->slug : 'all' }}"
                             data-name="{{ strtolower($p->name) }}"
                             data-price="{{ $p->price }}"
                             data-rating="{{ $p->rating }}"
                             data-order="{{ $i }}">
                        <a href="/shop/{{ $p->slug }}" class="mk-thumb">
                            <img src="{{ $p->photo_url }}"
                                 alt="{{ $p->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='/frontend/img/no-image.png';">
                            @if ($off > 0)<span class="mk-deal">{{ $off }}% OFF</span>@endif
                        </a>
                        <div class="mk-info">
                            <div class="mk-cat-tag">{{ $p->category ? $p->category->name : 'OEM Part' }}</div>
                            <h4 class="mk-name"><a href="/shop/{{ $p->slug }}" title="{{ $p->name }}">{{ $p->name }}</a></h4>
                            <div class="mk-rate" aria-label="Rated {{ $p->rating }} out of 5">
                                <span class="mk-stars" aria-hidden="true">{{ str_repeat('★', $full) }}<span class="off">{{ str_repeat('★', 5 - $full) }}</span></span>
                                <span>({{ $p->reviews_count ?: 10 }})</span>
                            </div>
                            <div class="mk-price">
                                <b>${{ number_format($p->price, 2) }}</b>
                                @if ($p->compare_price && $p->compare_price > $p->price)
                                    <s>${{ number_format($p->compare_price, 2) }}</s>
                                    <span>Save ${{ number_format($p->compare_price - $p->price, 2) }}</span>
                                @endif
                            </div>
                            <div class="mk-actions">
                                <button type="button" class="mk-btn mk-btn--cart" onclick="addToCartGlobal({{ $p->id }}, 1)">
                                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                </button>
                                <a href="/shop/{{ $p->slug }}" class="mk-btn mk-btn--buy">Part Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="fas fa-search fa-3x mb-3 text-secondary"></i>
                        <h5>No auto parts currently cataloged in this category.</h5>
                        <p>Check back shortly or request an immediate quote from our sourcing team.</p>
                        <a href="/contact" class="btn btn-primary mt-2">Request Part Search</a>
                    </div>
                @endforelse
            </div>

            <div class="mk-empty" id="mkEmpty">
                <p>No parts match your filter criteria.</p>
                <a href="/contact" style="display:inline-block; margin-top:8px; padding:8px 16px; background:#0c2957; color:#fff; border-radius:6px; text-decoration:none;">Request Part Quote</a>
                <br>
                <button type="button" id="mkReset" style="margin-top:12px; background:#e81c2e; color:#fff; border:none; padding:6px 14px; border-radius:4px; cursor:pointer;">Clear filters</button>
            </div>

            <!-- Laravel Pagination Links -->
            @if($products->hasPages())
                <div class="pagination-wrap">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
</main>

<script>
(function () {
    var grid    = document.getElementById('mkGrid');
    var cards   = Array.prototype.slice.call(grid.querySelectorAll('.mk-card'));
    var search  = document.getElementById('mkSearch');
    var select  = document.getElementById('mkCatSelect');
    var sort    = document.getElementById('mkSort');
    var result  = document.getElementById('mkResult');
    var empty   = document.getElementById('mkEmpty');
    var chips   = document.querySelectorAll('.mk-chip');
    var activeCat = '{{ $categorySlug ?: "all" }}';

    function setCat(cat) {
        activeCat = cat;
        select.value = cat;
        chips.forEach(function (c) { c.classList.toggle('is-active', c.dataset.cat === cat); });
        apply();
    }

    function apply() {
        var q = search.value.trim().toLowerCase();
        var shown = 0;

        cards.forEach(function (c) {
            var ok = (activeCat === 'all' || c.dataset.cat === activeCat) &&
                     (!q || c.dataset.name.indexOf(q) !== -1);
            c.hidden = !ok;
            if (ok) shown++;
        });

        var mode = sort.value;
        cards.slice().sort(function (a, b) {
            if (mode === 'price-asc')  return a.dataset.price - b.dataset.price;
            if (mode === 'price-desc') return b.dataset.price - a.dataset.price;
            if (mode === 'rating')     return b.dataset.rating - a.dataset.rating;
            if (mode === 'name')       return a.dataset.name.localeCompare(b.dataset.name);
            return a.dataset.order - b.dataset.order;
        }).forEach(function (c) { grid.appendChild(c); });

        result.innerHTML = shown
            ? 'Showing <strong>' + shown + '</strong> of ' + cards.length + ' tested OEM parts'
            : 'No matching parts found';
        if (empty) {
            empty.classList.toggle('is-visible', shown === 0 && cards.length > 0);
        }
    }

    chips.forEach(function (c) {
        c.addEventListener('click', function () {
            setCat(c.dataset.cat);
        });
    });

    select.addEventListener('change', function () { setCat(select.value); });
    search.addEventListener('input', apply);
    search.addEventListener('keydown', function (e) { if (e.key === 'Enter') apply(); });
    document.getElementById('mkGo').addEventListener('click', apply);
    sort.addEventListener('change', apply);

    var resetBtn = document.getElementById('mkReset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            search.value = '';
            setCat('all');
        });
    }

    apply();
})();
</script>
@endsection