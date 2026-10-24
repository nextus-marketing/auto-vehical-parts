<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        $successCount = Order::where('payment_status', 'SUCCESS')->count();
        $failedCount = Order::where('payment_status', 'FAILED')->count();
        $allCount = Order::count();

        return view('Admin.Orders.index', compact('type', 'successCount', 'failedCount', 'allCount'));
    }

    public function data(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->has('payment_status') && !empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }

        return DataTables::eloquent($query)
            ->editColumn('order_number', function ($order) {
                return '<strong class="text-primary font-monospace">' . e($order->order_number) . '</strong>' .
                    '<br><small class="text-muted">' . $order->created_at->format('M d, Y h:i A') . '</small>';
            })
            ->editColumn('customer_name', function ($order) {
                return '<div><strong>' . e($order->customer_name) . '</strong></div>' .
                    '<small class="text-muted"><i class="ti ti-mail"></i> ' . e($order->customer_email) . '</small><br>' .
                    '<small class="text-muted"><i class="ti ti-phone"></i> ' . e($order->customer_phone) . '</small>';
            })
            ->editColumn('vehicle_details', function ($order) {
                $veh = $order->vehicle_details ?: 'Vehicle not specified';
                $vin = $order->vin_number ? '<br><small class="badge bg-light text-dark font-monospace">VIN: ' . e($order->vin_number) . '</small>' : '';
                return '<div>' . e($veh) . '</div>' . $vin;
            })
            ->editColumn('total_amount', function ($order) {
                $html = '<strong class="fs-4 text-dark">$' . number_format($order->total_amount, 2) . '</strong>';
                if ($order->discount_amount > 0) {
                    $html .= '<br><small class="text-success">Coupon -$' . number_format($order->discount_amount, 2) . '</small>';
                }
                return $html;
            })
            ->editColumn('payment_status', function ($order) {
                if ($order->payment_status === 'SUCCESS') {
                    return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="ti ti-check me-1"></i> SUCCESS</span>';
                } elseif ($order->payment_status === 'FAILED') {
                    return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="ti ti-alert-triangle me-1"></i> FAILED</span>' .
                        ($order->failure_reason ? '<br><small class="text-danger small">' . \Str::limit($order->failure_reason, 35) . '</small>' : '');
                } else {
                    return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="ti ti-clock me-1"></i> ' . e($order->payment_status) . '</span>';
                }
            })
            ->editColumn('order_status', function ($order) {
                $badges = [
                    'CONFIRMED' => 'bg-info',
                    'PROCESSING' => 'bg-primary',
                    'SHIPPED' => 'bg-secondary',
                    'DELIVERED' => 'bg-success',
                    'FAILED' => 'bg-danger',
                    'CANCELLED' => 'bg-dark',
                ];
                $bg = $badges[$order->order_status] ?? 'bg-secondary';
                return '<span class="badge ' . $bg . '">' . e($order->order_status) . '</span>';
            })
            ->addColumn('action', function ($order) {
                $delete = '<button class="btn btn-sm btn-danger btn-delete-order" data-id="' . $order->id . '" data-name="' . e($order->order_number) . '" title="Delete Order"><i class="ti ti-trash"></i></button>';
                return $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['order_number', 'customer_name', 'vehicle_details', 'total_amount', 'payment_status', 'order_status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    public function show($id)
    {
        // Decode hashid if needed
        $decodedId = \Hashids::connection(Order::class)->decode($id)[0] ?? $id;
        $order = Order::with('items.product')->findOrFail($decodedId);
        return view('Admin.Orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Order ' . $order->order_number . ' deleted permanently.'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($request->has('order_status')) {
            $order->order_status = $request->order_status;
        }
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully!',
        ]);
    }
}
