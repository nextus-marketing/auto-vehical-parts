<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Category;
use Illuminate\Support\Str;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'status',
        'name',
        'slug',
        'index',
        'price',
        'compare_price',
        'short_description',
        'description',
        'part_number',
        'warranty',
        'mileage',
        'fitment',
        'stock_status',
        'rating',
        'reviews_count',
        'home_featured',
        'photo',
        'other_photo',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'other_photo' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedComparePriceAttribute()
    {
        return $this->compare_price ? '$' . number_format($this->compare_price, 2) : null;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return '/frontend/img/shop/shop1.jpg';
        }
        if (Str::startsWith($this->photo, ['http://', 'https://', '/'])) {
            return $this->photo;
        }
        return '/storage/' . $this->photo;
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
