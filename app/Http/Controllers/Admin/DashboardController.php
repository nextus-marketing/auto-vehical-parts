<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Employee;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Date filter
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   Carbon::now()->toDateString());

        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        // ── Core Counts ────────────────────────────────────────────────
        $totalOrders   = Order::count();
        $successOrders = Order::where('payment_status', 'SUCCESS')->count();
        $failedOrders  = Order::where('payment_status', 'FAILED')->count();
        $pendingOrders = Order::whereNotIn('payment_status', ['SUCCESS', 'FAILED'])->count();

        // ── Revenue ────────────────────────────────────────────────────
        $totalRevenue    = Order::where('payment_status', 'SUCCESS')->sum('total_amount');
        $totalDiscount   = Order::where('payment_status', 'SUCCESS')->sum('discount_amount');

        // ── Filtered Counts ─────────────────────────────────────────────
        $filteredSuccess = Order::where('payment_status', 'SUCCESS')
                                ->whereBetween('created_at', [$from, $to])->count();
        $filteredFailed  = Order::where('payment_status', 'FAILED')
                                ->whereBetween('created_at', [$from, $to])->count();
        $filteredRevenue = Order::where('payment_status', 'SUCCESS')
                                ->whereBetween('created_at', [$from, $to])
                                ->sum('total_amount');

        // ── Other Counts ────────────────────────────────────────────────
        $totalProducts = Product::count();
        $totalEnquiries = Enquiry::count();
        $totalEmployees = Employee::count();
        $totalUsers = User::count();

        // ── Monthly Sales Chart (last 12 months) ───────────────────────
        $monthlySales = Order::where('payment_status', 'SUCCESS')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(total_amount) as revenue"),
                DB::raw("COUNT(*) as order_count")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartMonths  = [];
        $chartRevenue = [];
        $chartOrders  = [];

        // Fill all 12 months even if no data
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->format('M Y');
            $chartMonths[] = $label;
            $row = $monthlySales->firstWhere('month', $m);
            $chartRevenue[] = $row ? (float) $row->revenue : 0;
            $chartOrders[]  = $row ? (int)   $row->order_count : 0;
        }

        // ── Order Status Distribution ──────────────────────────────────
        $orderStatusData = Order::select('order_status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('order_status')
            ->pluck('cnt', 'order_status')
            ->toArray();

        // ── Recent Orders ──────────────────────────────────────────────
        $recentOrders = Order::latest()->take(8)->get();

        // ── Top Products ───────────────────────────────────────────────
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'SUCCESS')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        return view('Admin.Dashboard.index', compact(
            'totalOrders', 'successOrders', 'failedOrders', 'pendingOrders',
            'totalRevenue', 'totalDiscount',
            'filteredSuccess', 'filteredFailed', 'filteredRevenue',
            'totalProducts', 'totalEnquiries', 'totalEmployees', 'totalUsers',
            'chartMonths', 'chartRevenue', 'chartOrders',
            'orderStatusData', 'recentOrders', 'topProducts',
            'dateFrom', 'dateTo'
        ));
    }
}