<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends BaseModel
{
    protected $fillable = ['title','images','links','status'];

    protected $casts = [
        'images' => 'array',
        'links' => 'array',
    ];
}
