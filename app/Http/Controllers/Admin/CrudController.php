<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Crud;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CrudController extends Controller
{
    public function index()
    {
        return view('Admin.Cruds.index');
    }

    public function data(Request $request)
    {
        $query = Crud::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('table_name', function ($crud) {
                return $crud->table_name;
            })
            ->editColumn('model_name', function ($crud) {
                return $crud->model_name;
            })
            ->editColumn('controller_name', function ($crud) {
                return $crud->controller_name;
            })
            ->editColumn('route_name', function ($crud) {
                return $crud->route_name;
            })
            ->editColumn('view_folder_name', function ($crud) {
                return $crud->view_folder_name;
            })
            ->editColumn('fields', function ($crud) {
                $fields = '';
                foreach($crud->fields as $key => $field){
                    if(isset($field['field']) && $field['field'] != ''){
                        $fields .= $field['field'].', ';
                    }
                }
                return $fields;
            })
            ->addColumn('action', function ($crud) {
                $edit  = '<a href="'.route('admin.cruds.edit',['crud' => $crud->route_key]).'" class="badge bg-warning fs-1"><i class="ti ti-edit fs-3"></i></a>';
                $generate  = '<a href="'.route('admin.cruds.generate',['crud' => $crud->route_key]).'" class="badge bg-danger fs-1 crud-create"><i class="ti ti-player-play fs-3"></i></a>';
                $undo  = '<a href="'.route('admin.cruds.undo',['crud' => $crud->route_key]).'" class="badge bg-info fs-1 crud-undo"><i class="ti ti-arrow-back-up fs-3"></i></a>';
                return $edit.' '.$generate.' '.$undo;
            })
            ->addIndexColumn()
            ->rawColumns(['table_name','model_name','controller_name','route_name','view_folder_name','fields','action'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $cruds = Crud::all();
        return view('Admin.Cruds.index', compact('cruds'));
    }


    public function create()
    {
        return view('Admin.Cruds.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $crud = new Crud;
        $crud->fill($request->all());
        $crud->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Crud Created Successfully',
            'crud' => $crud
        ], 201);
    }

    public function edit(Crud $crud)
    {
        return view('Admin.Cruds.form', compact('crud'));
    }

    public function update(Request $request, Crud $crud)
    {
        $this->rules['table_name'] = 'required|unique:cruds,table_name,'.$crud->id;
        $this->rules['model_name'] = 'required|unique:cruds,model_name,'.$crud->id;
        $this->rules['singular_name'] = 'required|unique:cruds,singular_name,'.$crud->id;
        $this->rules['controller_name'] = 'required|unique:cruds,controller_name,'.$crud->id;
        $this->rules['route_name'] = 'required|unique:cruds,route_name,'.$crud->id;
        $this->rules['view_folder_name'] = 'required|unique:cruds,view_folder_name,'.$crud->id;

        $request->validate($this->rules, $this->customMessages);

        $crud->fill($request->all());
        $crud->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Crud Updated Successfully',
            'crud' => $crud
        ], 201);
    }

    public function destroy(Crud $crud)
    {
        // Implement the logic for deleting the crud
    }

    private $rules = [
        'table_name' => 'required|unique:cruds,table_name',
        'model_name' => 'required|unique:cruds,model_name',
        'singular_name' => 'required|unique:cruds,singular_name',
        'controller_name' => 'required|unique:cruds,controller_name',
        'route_name' => 'required|unique:cruds,route_name',
        'view_folder_name' => 'required|unique:cruds,view_folder_name',
        'fields.*' => 'required|array|min:1',
        'validations.*' => 'required|array|min:1',
        'messages.*' => 'required|array|min:1',
    ];

    private $customMessages = [
        'table_name.required' => 'Table Name is required',
        'table_name.unique' => 'Table Name is already taken',
        'model_name.required' => 'Model Name is required',
        'model_name.unique' => 'Model Name is already taken',
        'singular_name.required' => 'Singular Name is required',
        'singular_name.unique' => 'Singular Name is already taken',
        'controller_name.required' => 'Controller Name is required',
        'controller_name.unique' => 'Controller Name is already taken',
        'route_name.required' => 'Route Name is required',
        'route_name.unique' => 'Route Name is already taken',
        'view_folder_name.required' => 'View Folder Name is required',
        'view_folder_name.unique' => 'View Folder Name is already taken',
        'fields.*.required' => 'Fields are required',
        'fields.*.array' => 'Fields must be an array',
        'validations.*.required' => 'Validations are required',
        'validations.*.array' => 'Validations must be an array',
        'messages.*.required' => 'Messages are required',
        'messages.*.array' => 'Messages must be an array',
    ];


    public function generate(Crud $crud){
        $exitCode = \Artisan::call('crud:create', ['id' => $crud->id]);
        $exitCode = \Artisan::call('migrate');
        $exitCode = \Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);

        return response()->json([
            'status' => 'success',
            'message' => 'Crud Created Successfully',
        ], 201);
    }

    public function undo(Crud $crud){
        $exitCode = \Artisan::call('crud:undo', ['id' => $crud->id]);
        \Schema::dropIfExists($crud->table_name);
        $exitCode = \Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);

        return response()->json([
            'status' => 'success',
            'message' => 'Crud Deleted Successfully',
        ], 201);
    }
}
