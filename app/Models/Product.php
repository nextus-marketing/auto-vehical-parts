<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use App\Models\BaseModel;
use App\Models\Category;
use App\Models\MultiplePhoto;

class Product extends BaseModel
{
    use HasFactory;
     
    protected $fillable = [
        'category_id',
        'status',
        'name',
        'slug',
        'index',
        'home_featured',
        'description',
        'photo',
        'other_photo'
    ];

   

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

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = \Str::slug($product->name);
        });
    }
}
