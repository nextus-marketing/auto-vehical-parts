<?php

namespace App\Models;

use App\Models\BaseModel;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;
    protected $fillable = ['status','name','slug','index','description','home_featured'];

   

}
