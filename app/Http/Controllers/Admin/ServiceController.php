<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        return view('Admin.Services.index');
    }

    public function data(Request $request)
    {
        $query = Service::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('category_id', function ($service) {
                return $service->category->name;
            })
            ->editColumn('name', function ($service) {
                return $service->name;
            })
            ->editColumn('slug', function ($service) {
                return $service->slug;
            })
            ->editColumn('index', function ($service) {
                return $service->index;
            })
            ->editColumn('description', function ($service) {
                return $service->description;
            })
            ->editColumn('photo', function ($service) {
                return $service->photo;
            })
            ->editColumn('home_featured', function ($service) {
                if ($service->home_featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input services-home_featured-switch" type="checkbox" data-column="home_featured" checked data-routekey="' . $service->route_key . '" /></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input services-home_featured-switch" type="checkbox" data-column="home_featured" data-routekey="' . $service->route_key . '" /></div>';
                }
            })
            ->editColumn('status', function ($service) {
                if ($service->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input services-status-switch" type="checkbox" checked data-routekey="' . $service->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input services-status-switch" type="checkbox" data-routekey="' . $service->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($service) {
                $edit  = '<a href="' . route('admin.services.edit', ['service' => $service->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $show = '<a href="' . route('admin.services.show', ['service' => $service->id]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="services" data-title="All Inclusive Service Details" data-route-key="' . $service->route_key . '"><i class="fa fa-eye"></i></a>';
                return $edit . ' ' . $show;
            })
            ->addIndexColumn()
            ->rawColumns(['category_id', 'name', 'slug', 'index', 'description', 'photo', 'other_photo', 'action', 'status', 'home_featured'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $services = Service::all();
        return view('Admin.Services.index', compact('services'));
    }


    public function create()
    {
        $categories = Category::where('status', 'ACTIVE')->get(); // Retrieve only active categories from the database
        return view('Admin.Services.form', compact('categories'));
    }

    public function getCategoryName(Service $service)
    {
        $categoryName = $service->category->name;
        return response()->json([
            'status' => 'success',
            'category_name' => $categoryName,
        ], 200);
    }

    public function store(Request $request)
    {

        $request->validate($this->rules, $this->customMessages);
        // check if the index is unique within the same category ::
        $this->rules['index'] = 'required|unique:services,index,NULL,id,category_id,' . $request->category_id;

        $request->validate($this->rules, $this->customMessages);

        $service = new Service;
        $service->slug = Str::slug($request->slug);
        $service->fill($request->all());
        if ($request->hasFile('photo')) {
            $service->photo = Storage::disk('public')->put('photos', $request->file('photo'));
        }
        $websiteOtherImages = [];
        if ($request->hasFile('other_photo')) {
            $websiteOtherImages = [];
            foreach ($request->file('other_photo') as $file) {
                $image_path = Storage::disk('public')->put('other_photo', $file);
                $websiteOtherImages[] = $image_path;
            }
            $service->other_photo = ($websiteOtherImages);
        }
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service Created Successfully',
            'service' => $service
        ], 201);
    }

    public function show(Service $service)
    {
        return view('Admin.Services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = Category::all(); // Retrieve all categories from the database
        return view('Admin.Services.form', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        // Modify the validation rules to include slug, photo, and secondary_photo checks
        $this->rules['slug'] = 'required|unique:services,slug,' . $service->id;
        $this->rules['photo'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        // check if the index is unique within the same category
        $this->rules['index'] = 'required|unique:services,index,' . $service->id . ',id,category_id,' . $service->category_id;

        $request->validate($this->rules, $this->customMessages);

        // Extract the deleted image sources from the request and compare with the existing other photos
        $delete_images_source_src = preg_split("/\,/", $request->delete_images_source);
        $website_images_old_image = $service->other_photo ? array_diff($service->other_photo, $delete_images_source_src) : [];


        // Assign the updated values from the request to the Service instance
        $service->fill($request->all());
        $service->slug = Str::slug($request->slug);
        // Handle the main photo if it is present in the request
        if ($request->hasFile('photo')) {
            if ($service->photo) {
                Storage::disk('public')->delete($service->photo);
            }
            $service->photo = Storage::disk('public')->put('photos', $request->file('photo'));
        }

        // Handle other photos in the request, storing them in the storage directory
        $other_images = [];
        if ($request->hasFile('other_photo')) {
            foreach ($request->other_photo as $image_file) {
                $image_path =  Storage::disk('public')->put('other_photo', $image_file);
                $other_images[] = $image_path;
            }
        }
        $service->other_photo = array_merge($other_images, $website_images_old_image);

        // Save the updated Service instance
        $service->save();

        // Return a success response in JSON format
        return response()->json([
            'status' => 'success',
            'message' => 'Service Updated Successfully',
            'service' => $service
        ], 201);
    }

    public function destroy(Service $service)
    {
        // Implement the logic for deleting the service
    }

    public function changeHomeFeaturedStatus(Request $request)
    {
        $service = Service::findByKey($request->route_key);
        $service->home_featured = $request->status;
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => $service->name . ' has been marked ' . $service->home_featured . ' successfully',
            'service' => $service
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $service = Service::findByKey($request->route_key);
        $service->status = $request->status;
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => $service->name . ' has been marked ' . $service->status . ' successfully',
            'service' => $service
        ], 201);
    }

    private $rules = [
        'category_id' => 'required',
        'name' => 'required',
        'slug' => 'required|unique:services,slug',
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
