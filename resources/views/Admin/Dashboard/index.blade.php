@extends('layouts.admin')
@section('title') Dashboard @endsection

@push('styles')
<style>
/* ── Dashboard Scoped Styles ─────────────────────────────── */
.dash-page { background: #f4f6fa; min-height: 100vh; padding: 1.5rem; }

/* Stat Cards */
.stat-card {
    border-radius: 16px;
    border: none;
    padding: 22px 20px;
    position: relative;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
    box-shadow: 0 4px 18px rgba(0,0,0,.04);
}
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,.08); }
.stat-card .icon-wrap {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; flex-shrink: 0;
}
.stat-card .stat-value { font-size: 1.9rem; font-weight: 700; line-height: 1.1; margin-bottom: 4px; }
.stat-card .stat-label { font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; opacity: .75; }
.stat-card .bg-blob {
    position: absolute; right: -25px; bottom: -25px;
    width: 110px; height: 110px; border-radius: 50%; opacity: .08;
    pointer-events: none;
}

/* Card variants */
.card-success  { background: linear-gradient(135deg,#e8f8ef,#ffffff); border: 1px solid #d1fae5; }
.card-success  .icon-wrap { background: #10b98120; color: #10b981; }
.card-success  .stat-value, .card-success .text-theme { color: #059669; }
.card-success  .bg-blob { background: #10b981; }

.card-danger   { background: linear-gradient(135deg,#fef2f2,#ffffff); border: 1px solid #fee2e2; }
.card-danger   .icon-wrap { background: #ef444420; color: #ef4444; }
.card-danger   .stat-value, .card-danger .text-theme { color: #dc2626; }
.card-danger   .bg-blob { background: #ef4444; }

.card-warning  { background: linear-gradient(135deg,#fffbeb,#ffffff); border: 1px solid #fef3c7; }
.card-warning  .icon-wrap { background: #f59e0b20; color: #f59e0b; }
.card-warning  .stat-value, .card-warning .text-theme { color: #d97706; }
.card-warning  .bg-blob { background: #f59e0b; }

.card-primary  { background: linear-gradient(135deg,#eff6ff,#ffffff); border: 1px solid #dbeafe; }
.card-primary  .icon-wrap { background: #3b82f620; color: #3b82f6; }
.card-primary  .stat-value, .card-primary .text-theme { color: #2563eb; }
.card-primary  .bg-blob { background: #3b82f6; }

.card-purple   { background: linear-gradient(135deg,#faf5ff,#ffffff); border: 1px solid #f3e8ff; }
.card-purple   .icon-wrap { background: #8b5cf620; color: #8b5cf6; }
.card-purple   .stat-value, .card-purple .text-theme { color: #7c3aed; }
.card-purple   .bg-blob { background: #8b5cf6; }

.card-teal     { background: linear-gradient(135deg,#f0fdfa,#ffffff); border: 1px solid #ccfbf1; }
.card-teal     .icon-wrap { background: #14b8a620; color: #14b8a6; }
.card-teal     .stat-value, .card-teal .text-theme { color: #0d9488; }
.card-teal     .bg-blob { background: #14b8a6; }

.card-indigo   { background: linear-gradient(135deg,#eef2ff,#ffffff); border: 1px solid #e0e7ff; }
.card-indigo   .icon-wrap { background: #6366f120; color: #6366f1; }
.card-indigo   .stat-value, .card-indigo .text-theme { color: #4f46e5; }
.card-indigo   .bg-blob { background: #6366f1; }

.card-orange   { background: linear-gradient(135deg,#fff7ed,#ffffff); border: 1px solid #ffedd5; }
.card-orange   .icon-wrap { background: #f9731620; color: #f97316; }
.card-orange   .stat-value, .card-orange .text-theme { color: #ea580c; }
.card-orange   .bg-blob { background: #f97316; }

/* Modern panels */
.chart-card {
    border-radius: 16px; border: 1px solid #edf2f7;
    box-shadow: 0 4px 18px rgba(0,0,0,.03);
    background: #fff;
    overflow: hidden;
}
.chart-card .card-header-custom {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.chart-card .card-title-custom { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; }

/* Filter bar */
.filter-bar {
    background: #fff;
    border-radius: 16px;
    padding: 18px 22px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 10px rgba(0,0,0,.03);
    margin-bottom: 24px;
}

/* Orders table */
.orders-table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #64748b; border: none; background: #f8fafc; padding: 12px 14px; }
.orders-table td { vertical-align: middle; border-color: #f1f5f9; font-size: .88rem; padding: 12px 14px; }
.orders-table tr:hover td { background: #f8fafc; }

/* Mini status badge */
.badge-status { padding: 4px 10px; border-radius: 9999px; font-size: .74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
.badge-status.success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.badge-status.failed  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.badge-status.pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

/* Top products */
.top-product-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.top-product-row:last-child { border-bottom: none; }
.rank-badge { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 700; flex-shrink: 0; }
.mini-progress { height: 6px; border-radius: 9999px; background: #f1f5f9; overflow: hidden; margin-top: 5px; }
.mini-progress-fill { height: 100%; border-radius: 9999px; }

/* Headers */
.section-header { font-size: 1.35rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
.section-sub    { font-size: .86rem; color: #64748b; }
</style>
@endpush

@section('content')
<div class="dash-page">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="section-header">
                <i class="ti ti-chart-bar text-primary me-2"></i>Executive Store Dashboard
            </h4>
            <p class="section-sub mb-0">Overview of orders, sales profit, store performance & conversion analytics</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="ti ti-shopping-cart me-1"></i> View Orders
            </a>
            <a href="{{ route('admin.orders.index') }}?type=success" class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="ti ti-circle-check me-1"></i> Success Orders
            </a>
            <a href="{{ route('admin.orders.index') }}?type=failed" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="ti ti-alert-triangle me-1"></i> Failed Orders
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.dashboard.index') }}" class="row g-3 align-items-end">
            <div class="col-sm-4 col-md-3">
                <label class="form-label fw-semibold text-muted mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;">Start Date</label>
                <input type="date" name="date_from" class="form-control form-control-sm rounded-2" value="{{ $dateFrom }}">
            </div>
            <div class="col-sm-4 col-md-3">
                <label class="form-label fw-semibold text-muted mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;">End Date</label>
                <input type="date" name="date_to" class="form-control form-control-sm rounded-2" value="{{ $dateTo }}">
            </div>
            <div class="col-sm-4 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-2">
                    <i class="ti ti-filter me-1"></i> Filter Date
                </button>
            </div>
            <div class="col-auto ms-auto d-flex flex-wrap gap-2">
                @php
                    $today = now()->toDateString();
                    $startMonth = now()->startOfMonth()->toDateString();
                    $last7 = now()->subDays(6)->toDateString();
                    $last30 = now()->subDays(29)->toDateString();
                @endphp
                <a href="{{ route('admin.dashboard.index') }}?date_from={{ $startMonth }}&date_to={{ $today }}" class="btn btn-outline-primary btn-sm rounded-pill" style="font-size:.78rem;">This Month</a>
                <a href="{{ route('admin.dashboard.index') }}?date_from={{ $last7 }}&date_to={{ $today }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size:.78rem;">Last 7 Days</a>
                <a href="{{ route('admin.dashboard.index') }}?date_from={{ $last30 }}&date_to={{ $today }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size:.78rem;">Last 30 Days</a>
            </div>
        </form>
    </div>

    {{-- Filtered Summary (Date-Wise) --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="fw-bold text-dark text-uppercase mb-0" style="font-size:.8rem;letter-spacing:.06em;">
            <i class="ti ti-calendar me-1 text-primary"></i> Filtered Date Performance ({{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }})
        </h6>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card card-success h-100">
                <div class="bg-blob"></div>
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="ti ti-check"></i></div>
                    <div>
                        <div class="stat-value">{{ $filteredSuccess }}</div>
                        <div class="stat-label">Successful Orders</div>
                        <small class="text-theme fw-semibold" style="font-size:.75rem;">In selected date range</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-danger h-100">
                <div class="bg-blob"></div>
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="ti ti-x"></i></div>
                    <div>
                        <div class="stat-value">{{ $filteredFailed }}</div>
                        <div class="stat-label">Failed Orders</div>
                        <small class="text-theme fw-semibold" style="font-size:.75rem;">In selected date range</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-teal h-100">
                <div class="bg-blob"></div>
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="ti ti-currency-dollar"></i></div>
                    <div>
                        <div class="stat-value">${{ number_format($filteredRevenue, 2) }}</div>
                        <div class="stat-label">Sales Revenue</div>
                        <small class="text-theme fw-semibold" style="font-size:.75rem;">Completed payments</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- All-Time KPI Matrix --}}
    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size:.78rem;letter-spacing:.08em;">
        <i class="ti ti-dashboard me-1"></i> All-Time Store Totals
    </h6>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card card-success">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-circle-check"></i></div>
                <div class="stat-value">{{ $successOrders }}</div>
                <div class="stat-label">Total Success Orders</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-danger">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-alert-triangle"></i></div>
                <div class="stat-value">{{ $failedOrders }}</div>
                <div class="stat-label">Total Failed Orders</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-warning">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-clock"></i></div>
                <div class="stat-value">{{ $pendingOrders }}</div>
                <div class="stat-label">Pending / Processing</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-primary">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-shopping-cart"></i></div>
                <div class="stat-value">{{ $totalOrders }}</div>
                <div class="stat-label">Total Orders Placed</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-teal">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-cash"></i></div>
                <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-indigo">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-box"></i></div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Active Products</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-purple">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-users"></i></div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Registered Customers</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-orange">
                <div class="bg-blob"></div>
                <div class="icon-wrap mb-2"><i class="ti ti-messages"></i></div>
                <div class="stat-value">{{ $totalEnquiries }}</div>
                <div class="stat-label">Customer Inquiries</div>
            </div>
        </div>
    </div>

    {{-- Interactive Graphs --}}
    <div class="row g-4 mb-4">
        {{-- Revenue & Order Trend --}}
        <div class="col-xl-8">
            <div class="chart-card h-100">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="ti ti-trending-up me-2 text-primary"></i>Sales & Profit Trends
                        </div>
                        <small class="text-muted" style="font-size:.78rem;">Monthly completed sales volume & order count (12 Months)</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size:.75rem;">
                        ${{ number_format(array_sum($chartRevenue), 2) }} Total Sold
                    </span>
                </div>
                <div class="p-3">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Order Status Donut --}}
        <div class="col-xl-4">
            <div class="chart-card h-100">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="ti ti-chart-pie me-2 text-purple"></i>Order Status Breakdown
                        </div>
                        <small class="text-muted" style="font-size:.78rem;">Distribution of fulfillment status</small>
                    </div>
                </div>
                <div class="p-3 d-flex flex-column align-items-center">
                    <div style="position:relative; width: 200px; height: 200px;">
                        <canvas id="statusDonut"></canvas>
                    </div>
                    <div class="mt-3 w-100">
                        @php
                            $statusColors = [
                                'CONFIRMED'  => '#3b82f6',
                                'PROCESSING' => '#8b5cf6',
                                'SHIPPED'    => '#06b6d4',
                                'DELIVERED'  => '#10b981',
                                'FAILED'     => '#ef4444',
                                'CANCELLED'  => '#6b7280',
                            ];
                        @endphp
                        @forelse($orderStatusData as $status => $cnt)
                        <div class="d-flex align-items-center justify-content-between mb-1 py-1 border-bottom" style="font-size:.82rem;">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:10px;height:10px;border-radius:50%;background:{{ $statusColors[$status] ?? '#94a3b8' }};display:inline-block;"></span>
                                <span class="fw-semibold text-secondary">{{ $status }}</span>
                            </div>
                            <span class="fw-bold text-dark">{{ $cnt }}</span>
                        </div>
                        @empty
                        <p class="text-muted text-center my-3" style="font-size:.82rem;">No orders recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Row: Recent Orders & Top Selling Products --}}
    <div class="row g-4">
        {{-- Recent Orders Table --}}
        <div class="col-xl-7">
            <div class="chart-card h-100">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="ti ti-clock-hour-4 me-2 text-success"></i>Recent Transactions
                        </div>
                        <small class="text-muted" style="font-size:.78rem;">Latest orders placed in the system</small>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size:.75rem;">
                        View All
                    </a>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table orders-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Order Number</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-bold font-monospace text-primary" style="font-size:.82rem;">{{ $order->order_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark" style="font-size:.84rem;">{{ $order->customer_name }}</div>
                                        <small class="text-muted" style="font-size:.75rem;">{{ $order->customer_email }}</small>
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->payment_status === 'SUCCESS')
                                            <span class="badge-status success"><i class="ti ti-check"></i> SUCCESS</span>
                                        @elseif($order->payment_status === 'FAILED')
                                            <span class="badge-status failed"><i class="ti ti-alert-triangle"></i> FAILED</span>
                                        @else
                                            <span class="badge-status pending"><i class="ti ti-clock"></i> {{ $order->payment_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size:.78rem;">{{ $order->created_at->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No recent orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="col-xl-5">
            <div class="chart-card h-100">
                <div class="card-header-custom">
                    <div>
                        <div class="card-title-custom">
                            <i class="ti ti-flame me-2 text-warning"></i>Top Selling Products
                        </div>
                        <small class="text-muted" style="font-size:.78rem;">Highest revenue producing items</small>
                    </div>
                </div>
                <div class="p-3">
                    @php $maxRev = $topProducts->max('total_revenue') ?: 1; @endphp
                    @forelse($topProducts as $i => $prod)
                    @php
                        $rankColors = ['#f59e0b','#94a3b8','#b45309','#6366f1','#10b981'];
                        $barColors  = ['#f59e0b','#6366f1','#10b981','#3b82f6','#ef4444'];
                    @endphp
                    <div class="top-product-row">
                        <div class="rank-badge" style="background:{{ $rankColors[$i] ?? '#e2e8f0' }}25;color:{{ $rankColors[$i] ?? '#64748b' }};">
                            #{{ $i+1 }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark" style="font-size:.85rem;">{{ Str::limit($prod->name, 28) }}</span>
                                <span class="fw-bold text-success" style="font-size:.85rem;">${{ number_format($prod->total_revenue, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mini-progress flex-grow-1 me-3">
                                    <div class="mini-progress-fill" style="width:{{ ($prod->total_revenue / $maxRev) * 100 }}%;background:{{ $barColors[$i] ?? '#64748b' }};"></div>
                                </div>
                                <small class="text-muted fw-semibold" style="font-size:.72rem;">{{ $prod->total_qty }} sold</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-box" style="font-size:2rem;opacity:.3;"></i>
                        <p class="mt-2" style="font-size:.85rem;">No product sales records yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Data from Laravel backend
    const months      = @json($chartMonths);
    const revenueData = @json($chartRevenue);
    const orderData   = @json($chartOrders);

    // ── Revenue & Orders Double Axis Graph ──
    const rCanvas = document.getElementById('revenueChart');
    if (rCanvas) {
        const rCtx = rCanvas.getContext('2d');
        const revGrad = rCtx.createLinearGradient(0, 0, 0, 300);
        revGrad.addColorStop(0, 'rgba(59,130,246,0.25)');
        revGrad.addColorStop(1, 'rgba(59,130,246,0.0)');

        new Chart(rCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Sales Revenue ($)',
                        data: revenueData,
                        borderColor: '#2563eb',
                        backgroundColor: revGrad,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'yRevenue',
                    },
                    {
                        label: 'Completed Orders',
                        data: orderData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.05)',
                        borderWidth: 2,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        fill: false,
                        tension: 0.35,
                        yAxisID: 'yOrders',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { size: 12, weight: 600 } } },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.label.includes('Revenue')) {
                                    return ' ' + ctx.dataset.label + ': $' + Number(ctx.raw).toLocaleString(undefined, {minimumFractionDigits: 2});
                                }
                                return ' ' + ctx.dataset.label + ': ' + ctx.raw + ' orders';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: 500 }, color: '#64748b' }
                    },
                    yRevenue: {
                        type: 'linear', position: 'left',
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 11 },
                            color: '#2563eb',
                            callback: v => '$' + v.toLocaleString()
                        }
                    },
                    yOrders: {
                        type: 'linear', position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { font: { size: 11 }, color: '#10b981', stepSize: 1 }
                    }
                }
            }
        });
    }

    // ── Order Status Donut Chart ──
    const dCanvas = document.getElementById('statusDonut');
    @php
        $statusLabels = array_keys($orderStatusData);
        $statusValues = array_values($orderStatusData);
        $donutColors  = ['#3b82f6','#8b5cf6','#06b6d4','#10b981','#ef4444','#6b7280'];
    @endphp
    const statusLabels = @json($statusLabels);
    const statusValues = @json($statusValues);
    const donutColors  = @json($donutColors);

    if (dCanvas) {
        const dCtx = dCanvas.getContext('2d');
        if (statusValues.length > 0) {
            new Chart(dCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: donutColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + ctx.raw + ' orders'
                            }
                        }
                    }
                }
            });
        } else {
            new Chart(dCtx, {
                type: 'doughnut',
                data: {
                    labels: ['No Orders'],
                    datasets: [{ data: [1], backgroundColor: ['#e2e8f0'], borderWidth: 0 }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '72%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        }
    }
});
</script>
@endpush