<?php

namespace App\Http\Controllers\Admin;

use DataTables;
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
        $query = Product::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('category_id', function ($product) {
                return $product->category->name;
            })
            ->editColumn('name', function ($product) {
                return $product->name;
            })
            ->editColumn('slug', function ($product) {
                return $product->slug;
            })
            ->editColumn('index', function ($product) {
                return $product->index;
            })
            ->editColumn('description', function ($product) {
                return $product->description;
            })
            ->editColumn('photo', function ($product) {
                return $product->photo;
            })
            ->editColumn('home_featured', function ($product) {
                if ($product->home_featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input products-home_featured-switch" type="checkbox" data-column="home_featured" checked data-routekey="' . $product->route_key . '" /></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input products-home_featured-switch" type="checkbox" data-column="home_featured" data-routekey="' . $product->route_key . '" /></div>';
                }
            })
            ->editColumn('status', function ($product) {
                if ($product->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input products-status-switch" type="checkbox" checked data-routekey="' . $product->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input products-status-switch" type="checkbox" data-routekey="' . $product->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($product) {
                $edit  = '<a href="' . route('admin.products.edit', ['product' => $product->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $show = '<a href="/admin/products/'.$product->route_key.'" class="badge bg-info fs-1 modal-one-btn" data-entity="products" data-title="All Inclusive Product Details" data-route-key="' . $product->route_key . '"><i class="fa fa-eye"></i></a>';
                return $edit . ' ' . $show;
            })
            ->addIndexColumn()
            ->rawColumns(['category_id', 'name', 'slug', 'index', 'description', 'photo', 'other_photo', 'action', 'status', 'home_featured'])
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
        $categories = Category::where('status', 'ACTIVE')->get(); // Retrieve only active categories from the database
        return view('Admin.Products.form', compact('categories'));
    }

    public function getCategoryName(Product $product)
    {
        $categoryName = $product->category->name;
        return response()->json([
            'status' => 'success',
            'category_name' => $categoryName,
        ], 200);
    }

    public function store(Request $request)
    {

        $request->validate($this->rules, $this->customMessages);
        // check if the index is unique within the same category ::
        $this->rules['index'] = 'required|unique:products,index,NULL,id,category_id,' . $request->category_id;

        $request->validate($this->rules, $this->customMessages);

        $product = new Product;
        $product->slug = Str::slug($request->slug);
        $product->fill($request->all());
        if ($request->hasFile('photo')) {
            $product->photo = Storage::disk('public')->put('photos', $request->file('photo'));
        }
        $websiteOtherImages = [];
        if ($request->hasFile('other_photo')) {
            $websiteOtherImages = [];
            foreach ($request->file('other_photo') as $file) {
                $image_path = Storage::disk('public')->put('other_photo', $file);
                $websiteOtherImages[] = $image_path;
            }
            $product->other_photo = ($websiteOtherImages);
        }
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Created Successfully',
            'product' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        // dd(11);
        return view('Admin.Products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all(); // Retrieve all categories from the database
        return view('Admin.Products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Modify the validation rules to include slug, photo, and secondary_photo checks
        $this->rules['slug'] = 'required|unique:products,slug,' . $product->id;
        $this->rules['photo'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        // check if the index is unique within the same category
        $this->rules['index'] = 'required|unique:products,index,' . $product->id . ',id,category_id,' . $product->category_id;

        $request->validate($this->rules, $this->customMessages);

        // Extract the deleted image sources from the request and compare with the existing other photos
        $delete_images_source_src = preg_split("/\,/", $request->delete_images_source);
        $website_images_old_image = $product->other_photo ? array_diff($product->other_photo, $delete_images_source_src) : [];


        // Assign the updated values from the request to the Product instance
        $product->fill($request->all());
        $product->slug = Str::slug($request->slug);
        // Handle the main photo if it is present in the request
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $product->photo = Storage::disk('public')->put('photos', $request->file('photo'));
        }

        // Handle other photos in the request, storing them in the storage directory
        $other_images = [];
        if ($request->hasFile('other_photo')) {
            foreach ($request->other_photo as $image_file) {
                $image_path =  Storage::disk('public')->put('other_photo', $image_file);
                $other_images[] = $image_path;
            }
        }
        $product->other_photo = array_merge($other_images, $website_images_old_image);

        // Save the updated Product instance
        $product->save();

        // Return a success response in JSON format
        return response()->json([
            'status' => 'success',
            'message' => 'Product Updated Successfully',
            'product' => $product
        ], 201);
    }



    public function destroy(Product $product)
    {
        // Implement the logic for deleting the product
    }

    public function changeHomeFeaturedStatus(Request $request)
    {
        $product = Product::findByKey($request->route_key);
        $product->home_featured = $request->status;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => $product->name . ' has been marked ' . $product->home_featured . ' successfully',
            'product' => $product
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $product = Product::findByKey($request->route_key);
        $product->status = $request->status;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => $product->name . ' has been marked ' . $product->status . ' successfully',
            'product' => $product
        ], 201);
    }

    private $rules = [
        'category_id' => 'required',
        'name' => 'required',
        'slug' => 'required|unique:products,slug',
        'index' => 'required',
        'description' => 'required',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    private $customMessages = [
        'category_id.required' => 'Category ID is required',
        'name.required' => 'Name is required',
        'slug.required' => 'Slug is required',
        'slug.unique' => 'This Slug is already taken',
        'index.required' => 'Index is required',
        'description.required' => 'Description is required',
        'photo.required' => 'Main photo is required',
        'photo.image' => 'Main photo must be an image',
        'photo.mimes' => 'Main photo must be a file of type: jpeg, png, jpg, gif, svg',
        'photo.max' => 'Main photo size should not exceed 2048 KB',
    ];
}

