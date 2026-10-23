<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Crud extends  BaseModel
{
    protected $fillable = ['table_name','model_name','singular_name','controller_name','route_name','view_folder_name','fields','validations','messages','status'];

    protected $casts = [
        'fields' => 'array',
        'validations' => 'array',
        'messages' => 'array',
    ];
}
