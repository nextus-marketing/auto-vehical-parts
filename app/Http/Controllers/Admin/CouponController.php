<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        return view('Admin.Coupons.index');
    }

    public function data(Request $request)
    {
        $query = Coupon::latest();

        return DataTables::eloquent($query)
            ->editColumn('code', function ($coupon) {
                return '<span class="badge bg-primary fs-3 px-3 py-2 font-monospace">' . e($coupon->code) . '</span>' .
                    ($coupon->title ? '<div class="small text-muted mt-1">' . e($coupon->title) . '</div>' : '');
            })
            ->editColumn('discount_value', function ($coupon) {
                if ($coupon->discount_type === 'PERCENTAGE') {
                    return '<span class="fw-bold text-success">' . number_format($coupon->discount_value, 0) . '% OFF</span>';
                } else {
                    return '<span class="fw-bold text-success">$' . number_format($coupon->discount_value, 2) . ' OFF</span>';
                }
            })
            ->editColumn('min_order_amount', function ($coupon) {
                return '$' . number_format($coupon->min_order_amount, 2);
            })
            ->editColumn('status', function ($coupon) {
                $checked = ($coupon->status == 'ACTIVE') ? 'checked' : '';
                return '<div class="form-check form-switch"><input class="form-check-input coupons-status-switch" type="checkbox" ' . $checked . ' data-id="' . $coupon->id . '"/></div>';
            })
            ->editColumn('expires_at', function ($coupon) {
                if ($coupon->expires_at) {
                    $isExpired = now()->gt($coupon->expires_at->endOfDay());
                    return $coupon->expires_at->format('M d, Y') . ($isExpired ? ' <span class="badge bg-danger">Expired</span>' : '');
                }
                return '<span class="text-muted">No Expiry</span>';
            })
            ->addColumn('action', function ($coupon) {
                $edit = '<a href="' . route('admin.coupons.edit', ['coupon' => $coupon->id]) . '" class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i> Edit</a>';
                $delete = '<button class="btn btn-sm btn-danger btn-delete-coupon" data-id="' . $coupon->id . '" data-name="' . e($coupon->code) . '"><i class="ti ti-trash"></i> Delete</button>';
                return $edit . ' ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['code', 'discount_value', 'min_order_amount', 'status', 'expires_at', 'action', 'delete'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        return view('Admin.Coupons.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'discount_type' => 'required|in:PERCENTAGE,FIXED',
            'discount_value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon = new Coupon();
        $coupon->fill($request->all());
        $coupon->code = strtoupper(trim($request->code));
        $coupon->min_order_amount = $request->min_order_amount ?: 0.00;
        $coupon->status = $request->status ?: 'ACTIVE';
        $coupon->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon Code created successfully!',
            'coupon' => $coupon
        ], 201);
    }

    public function edit(Coupon $coupon)
    {
        return view('Admin.Coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:PERCENTAGE,FIXED',
            'discount_value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon->fill($request->all());
        $coupon->code = strtoupper(trim($request->code));
        $coupon->min_order_amount = $request->min_order_amount ?: 0.00;
        $coupon->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon Code updated successfully!',
            'coupon' => $coupon
        ], 200);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Coupon "' . $coupon->code . '" deleted permanently.'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        $coupon->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon ' . $coupon->code . ' status set to ' . $coupon->status,
        ]);
    }
}
