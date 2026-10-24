<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'start_date',
        'expires_at',
        'usage_limit',
        'used_count',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'expires_at' => 'date',
    ];

    public function isValidForAmount($subtotal)
    {
        if ($this->status !== 'ACTIVE') {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->start_date && now()->lt($this->start_date)) {
            return false;
        }

        if ($this->expires_at && now()->gt($this->expires_at->endOfDay())) {
            return false;
        }

        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($subtotal)
    {
        if (!$this->isValidForAmount($subtotal)) {
            return 0.00;
        }

        if ($this->discount_type === 'PERCENTAGE') {
            $discount = ($subtotal * $this->discount_value) / 100;
        } else {
            $discount = min($this->discount_value, $subtotal);
        }

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return round($discount, 2);
    }
}
