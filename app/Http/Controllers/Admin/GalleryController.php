<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('Admin.Galleries.index');
    }



    public function data(Request $request){
        $query = Gallery::where('id','!=',0);

        return DataTables::eloquent($query)
            ->editColumn('datetime', function ($gallery) {
                return toIndianDateTime($gallery->created_at);
            }) 
            ->editColumn('images', function ($gallery) {
                return $gallery->images;
            }) 
            ->editColumn('links', function ($gallery) {
                return $gallery->links;
            }) 
            ->addColumn('action', function ($gallery) {
                $edit  = '<a href="'.route('admin.galleries.edit',['gallery' => $gallery->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })   
           ->addIndexColumn()
           ->rawColumns(['datetime','images','links','action'])->setRowId('id')->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Galleries.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        $gallery = new Gallery;
        $websiteOtherImages = [];
        if ($request->hasFile('images')) {
            $websiteOtherImages = [];
            foreach ($request->file('images') as $file) {
                $image_path = Storage::disk('public')->put('images', $file);
                $websiteOtherImages[] = $image_path;
            }
            $gallery->images = ($websiteOtherImages);
        }
        $gallery->links = $request->links;
        $gallery->save();

        return response()->json(['status' => 'success', 'message' => 'Gallery added successfully','gallery'=>$gallery]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        return view('Admin.Galleries.form',compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        
        $websiteOtherImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $image_path = Storage::disk('public')->put('images', $file);
                $websiteOtherImages[] = $image_path;
            }
        }

        // Assuming $gallery->images already contains an array of existing image paths
        // and you want to merge it with the new images
        $existingImages = is_array($gallery->images) ? $gallery->images : [];
        $gallery->images = array_merge($existingImages, $websiteOtherImages);
        $gallery->links = $request->links;
        $gallery->save();

        return response()->json(['status' => 'success', 'message' => 'Gallery updated successfully','gallery'=>$gallery]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private $rules = [
        'images' => 'required',
        'links' => 'required',
    ];


    private $messages = [
        'images.required' => 'Please enter images',
        'links.required' => 'Please enter links',
    ];  
}
