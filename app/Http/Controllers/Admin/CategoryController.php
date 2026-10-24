<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('Admin.Categories.index');
    }

    public function data(Request $request)
    {
        $query = Category::withCount('products')->orderBy('index', 'asc');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($category) {
                return '<strong>' . e($category->name) . '</strong>' .
                    '<br><small class="text-muted">' . $category->products_count . ' Parts Cataloged</small>';
            })
            ->editColumn('slug', function ($category) {
                return '<code>' . e($category->slug) . '</code>';
            })
            ->editColumn('index', function ($category) {
                return $category->index ?: '-';
            })
            ->editColumn('description', function ($category) {
                return Str::limit($category->description, 50);
            })
            ->editColumn('status', function ($category) {
                $checked = ($category->status == 'ACTIVE') ? 'checked' : '';
                return '<div class="form-check form-switch"><input class="form-check-input categories-status-switch" type="checkbox" ' . $checked . ' data-id="' . $category->id . '"/></div>';
            })
            ->addColumn('action', function ($category) {
                $edit = '<a href="' . route('admin.categories.edit', ['category' => $category->id]) . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="ti ti-edit"></i></a>';
                $shopLink = '<a href="/shop?category=' . $category->slug . '" target="_blank" class="btn btn-sm btn-info me-1" title="View in Shop"><i class="ti ti-external-link"></i></a>';
                $delete = '<button class="btn btn-sm btn-danger btn-delete-category" data-id="' . $category->id . '" data-name="' . e($category->name) . '" title="Delete"><i class="ti ti-trash"></i></button>';
                return $edit . ' ' . $shopLink . ' ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'slug', 'status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $categories = Category::where('status', 'ACTIVE')->orderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'list' => $categories
        ], 200);
    }

    public function create()
    {
        return view('Admin.Categories.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'index' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $category = new Category;
        $category->fill($request->all());
        $category->slug = Str::slug($request->slug ?: $request->name);
        $category->status = $request->status ?: 'ACTIVE';
        $category->home_featured = $request->home_featured ?: 'ACTIVE';
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category Created Successfully',
            'category' => $category
        ], 201);
    }

    public function edit(Category $category)
    {
        return view('Admin.Categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'index' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $category->fill($request->all());
        $category->slug = Str::slug($request->slug ?: $request->name);
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category Updated Successfully',
            'category' => $category
        ], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ]);
    }

    public function changeHomeFeaturedStatus(Request $request)
    {
        $category = Category::find($request->id ?: $request->route_key);
        if ($category) {
            $category->home_featured = $request->status;
            $category->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category featured status updated',
            'category' => $category
        ], 200);
    }

    public function changeStatus(Request $request)
    {
        $category = Category::find($request->id ?: $request->route_key);
        if ($category) {
            $category->status = $request->status;
            $category->home_featured = $request->status;
            $category->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category status updated to ' . $category->status,
            'category' => $category
        ], 200);
    }
}
