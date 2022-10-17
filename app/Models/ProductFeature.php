<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $table = 'product_feature';
    
    protected $guarded = [];
    
    use HasFactory;
}
