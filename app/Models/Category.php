<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'status',
        'name',
        'slug',
        'index',
        'image',
        'description',
        'home_featured',
        'created_by',
        'updated_by'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
