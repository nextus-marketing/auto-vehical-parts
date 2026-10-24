<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return view('Admin.Products.index');
    }

    public function data(Request $request)
    {
        $query = Product::with('category')->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('photo', function ($product) {
                return '<img src="' . $product->photo_url . '" width="55" height="55" class="rounded border" style="object-fit:cover;" />';
            })
            ->editColumn('category_id', function ($product) {
                return $product->category ? $product->category->name : '<span class="text-muted">Uncategorized</span>';
            })
            ->editColumn('name', function ($product) {
                return '<strong>' . e($product->name) . '</strong>' . ($product->part_number ? '<br><small class="text-muted">PN: ' . e($product->part_number) . '</small>' : '');
            })
            ->editColumn('price', function ($product) {
                $html = '<span class="fw-bold text-success">$' . number_format($product->price, 2) . '</span>';
                if ($product->compare_price) {
                    $html .= '<br><small class="text-decoration-line-through text-muted">$' . number_format($product->compare_price, 2) . '</small>';
                }
                return $html;
            })
            ->editColumn('slug', function ($product) {
                return '<code>' . e($product->slug) . '</code>';
            })
            ->editColumn('status', function ($product) {
                $checked = ($product->status == 'ACTIVE') ? 'checked' : '';
                return '<div class="form-check form-switch"><input class="form-check-input products-status-switch" type="checkbox" ' . $checked . ' data-routekey="' . $product->route_key . '"/></div>';
            })
            ->addColumn('action', function ($product) {
                $edit = '<a href="' . route('admin.products.edit', ['product' => $product->id]) . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="ti ti-edit"></i></a>';
                $view = '<a href="/shop/' . $product->slug . '" target="_blank" class="btn btn-sm btn-info me-1" title="View in Shop"><i class="ti ti-external-link"></i></a>';
                $delete = '<button class="btn btn-sm btn-danger btn-delete-product" data-id="' . $product->id . '" data-name="' . e($product->name) . '" title="Delete"><i class="ti ti-trash"></i></button>';
                return $edit . ' ' . $view . ' ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['photo', 'category_id', 'name', 'price', 'slug', 'action', 'status'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $products = Product::all();
        return view('Admin.Products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'ACTIVE')->orderBy('name')->get();
        return view('Admin.Products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $request->validate($rules, $this->customMessages);

        $product = new Product;
        $product->fill($request->except(['photo', 'other_photo']));
        $product->slug = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('photo')) {
            $product->photo = Storage::disk('public')->put('products', $request->file('photo'));
        }

        if ($request->hasFile('other_photo')) {
            $otherPhotos = [];
            foreach ($request->file('other_photo') as $file) {
                $otherPhotos[] = Storage::disk('public')->put('products', $file);
            }
            $product->other_photo = $otherPhotos;
        }

        $product->status = $request->status ?: 'ACTIVE';
        $product->home_featured = $product->status;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Created Successfully',
            'product' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        return view('Admin.Products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('Admin.Products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $rules = $this->rules;
        $rules['slug'] = 'required|unique:products,slug,' . $product->id;
        $rules['photo'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120';

        $request->validate($rules, $this->customMessages);

        $product->fill($request->except(['photo', 'other_photo', 'delete_images_source']));
        $product->slug = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('photo')) {
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
            $product->photo = Storage::disk('public')->put('products', $request->file('photo'));
        }

        // Handle other images deletion
        $currentOtherPhotos = $product->other_photo ?: [];
        if ($request->filled('delete_images_source')) {
            $deletedSources = explode(',', $request->delete_images_source);
            $currentOtherPhotos = array_values(array_diff($currentOtherPhotos, $deletedSources));
        }

        // Handle newly uploaded other images
        if ($request->hasFile('other_photo')) {
            foreach ($request->file('other_photo') as $file) {
                $currentOtherPhotos[] = Storage::disk('public')->put('products', $file);
            }
        }
        $product->other_photo = $currentOtherPhotos;

        $product->home_featured = $product->status ?: 'ACTIVE';
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Updated Successfully',
            'product' => $product
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    }

    public function changeHomeFeaturedStatus(Request $request)
    {
        $product = Product::findByKey($request->route_key);
        if (!$product) {
            $product = Product::find($request->route_key);
        }
        $product->home_featured = $request->status;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => $product->name . ' has been marked ' . $product->home_featured . ' successfully',
            'product' => $product
        ], 200);
    }

    public function changeStatus(Request $request)
    {
        $product = Product::findByKey($request->route_key);
        if (!$product) {
            $product = Product::find($request->route_key);
        }
        $product->status = $request->status;
        $product->home_featured = $request->status;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => $product->name . ' has been marked ' . $product->status . ' successfully',
            'product' => $product
        ], 200);
    }

    private $rules = [
        'category_id' => 'required',
        'name' => 'required',
        'slug' => 'required',
        'price' => 'required|numeric|min:0',
        'compare_price' => 'nullable|numeric|min:0',
        'short_description' => 'nullable',
        'description' => 'required',
        'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
    ];

    private $customMessages = [
        'category_id.required' => 'Category is required',
        'name.required' => 'Product Name is required',
        'slug.required' => 'Product Slug is required',
        'price.required' => 'Product Price in USD is required',
        'price.numeric' => 'Price must be a valid number',
        'description.required' => 'Full Description is required',
    ];
}
