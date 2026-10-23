<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    // Display orders index page
    public function index()
    {
        return view('Admin.Orders.index');
    }

    // Fetch data for DataTable
    public function data(Request $request)
    {
        $query = Order::query();

        // Dynamic filtering based on request parameters
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->editColumn('first_name', function ($order) {
                return $order->first_name;
            })
            ->editColumn('customer_id', function ($order) {
                return $order->customer_id;
            })
            ->editColumn('number', function ($order) {
                return $order->number;
            })
            ->editColumn('service_id', function ($order) {
                return $order->service_id;
            })
            ->editColumn('type', function ($order) {
                return $order->type;
            })
            ->editColumn('customer_addresses_id', function ($order) {
                return $order->customer_addresses_id;
            })
            ->editColumn('final_amount', function ($order) {
                return $order->final_amount;
            })
            ->editColumn('description', function ($order) {
                return $order->description;
            })
            ->editColumn('vendor_id', function ($order) {
                return $order->vendor_id;
            })
            ->editColumn('razorpay_order_id', function ($order) {
                return $order->razorpay_order_id;
            })
            ->addIndexColumn()
            ->rawColumns(['first_name', 'customer_id', 'service_id', 'type', 'customer_addresses_id', 'final_amount', 'description', 'vendor_id', 'razorpay_order_id'])
            ->setRowId('id')
            ->make(true);
    }

    // Fetch all orders for API or JSON response
    public function list()
    {
        $orders = Order::with('customer', 'vendor')->get(); // Eager loading related models for optimization
        return response()->json([
            'status' => 'success',
            'list' => $orders
        ], 200);
    }

    // Show create form
    public function create()
    {
        //
    }

    // Store new order in database
    public function store(Request $request)
    {
       //
    }

    // Show order details for editing
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('Admin.Orders.show', compact('order'));
    }

    // Show edit form
    public function edit($id)
    {
       //
    }

    // Update an existing order
    public function update(Request $request, $id)
    {
       //
    }

    // Delete an order
    public function destroy($id)
    {
       //
    }
}
