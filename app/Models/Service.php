<?php

namespace App\Models;

use App\Models\Category;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends BaseModel
{
    use HasFactory;
    protected $fillable = ['category_id','status','name','slug','index','home_featured','description','photo','other_photo'];

    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected $casts = [
        'other_photo' => 'array',
    ];

    public function multiplePhotos()
    {
        return $this->hasMany(MultiplePhoto::class);
    }
}
