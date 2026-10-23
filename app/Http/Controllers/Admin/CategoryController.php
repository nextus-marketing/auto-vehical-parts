<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Permissiongroup;
use View;

class CategoryController extends Controller
{
    public function index()
    {
        return view('Admin.Categories.index');
    }

    public function data(Request $request)
    {
        $query = Category::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($category) {
                return $category->name;
            })
            ->editColumn('slug', function ($category) {
                return $category->slug;
            })
            ->editColumn('index', function ($category) {
                return $category->index;
            })
            ->editColumn('description', function ($category) {
                return $category->description;
            })
            ->editColumn('home_featured', function ($category) {
                if ($category->home_featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-home_featured-switch" type="checkbox" data-column="home_featured"  checked data-routekey="' . $category->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-home_featured-switch" type="checkbox" data-column="home_featured"  data-routekey="' . $category->route_key . '"/></div>';
                }
            })
            ->editColumn('status', function ($category) {
                if ($category->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-status-switch" type="checkbox" checked data-routekey="' . $category->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-status-switch" type="checkbox" data-routekey="' . $category->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($category) {
                $edit  = '<a href="' . route('admin.categories.edit', ['category' => $category->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'name', 'slug', 'index', 'description', 'home_featured'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $categories = Category::all();
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
        $this->rules['index'] = 'required|numeric|unique:categories,index,NULL,id';

        $request->validate($this->rules, $this->customMessages);
        $category = new Category;
        $category->fill($request->all());
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
        $this->rules['slug'] = 'required|alpha_dash|unique:categories,slug,' . $category->id . ',id';
        $this->rules['index'] = 'required|numeric|unique:categories,index,' . $category->id . ',id';

        $request->validate($this->rules, $this->customMessages);
        $category->fill($request->all())->save();
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category Updated Successfully',
            'category' => $category
        ], 201);
    }

    public function destroy(Category $category)
    {
        // Implement the logic for deleting the category
    }

    public function changeHomeFeaturedStatus(Request $request)
    {
        $category = Category::findByKey($request->route_key);
        //dd($request->home_featured);
        $category->home_featured = $request->status;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => $category->name . ' has been marked ' . $category->home_featured . ' successfully',
            'category' => $category
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $category = Category::findByKey($request->route_key);
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => $category->name . ' has been marked ' . $category->status . ' successfully',
            'category' => $category
        ], 201);
    }

    private $rules = [
        'name' => 'required|regex:/^[\pL\s\-]+$/u',
        'slug' => 'required|alpha_dash|unique:categories,slug,',
        'index' => 'nullable|numeric',
        'description' => 'nullable|string',
    ];

    private $customMessages = [
        'name.required' => 'Category name is required',
        'name.regex' => 'Category name should contain only alphabets',
        'slug.required' => 'Slug is required',
        'slug.alpha_dash' => 'Slug should only contain letters, numbers, dashes, and underscores',
        'slug.unique' => 'This slug is already in use',
        'index.numeric' => 'Index should be a number',
        'description' => 'Description should be a string',
    ];
}
