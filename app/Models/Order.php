<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','number','service_id','type','customer_addresses_id','final_amount','description','vendor_id','razorpay_order_id'];
}
