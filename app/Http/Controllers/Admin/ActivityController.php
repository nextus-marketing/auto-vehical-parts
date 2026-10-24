<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    public function index()
    {
        return view('Admin.Activities.index');
    }

    public function data(Request $request)
    {
        $query = Activity::latest();
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function list()
    {
        return view('Admin.Activities.index');
    }

    public function show(Activity $activity)
    {
        return view('Admin.Activities.show', compact('activity'));
    }
}
