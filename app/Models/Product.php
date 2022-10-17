<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function features() {
        return $this->belongsToMany(Feature::class, 'product_feature');
    }

    public function productFeatures() {
        return $this->hasMany(ProductFeature::class);
    }

    public function featureValues()
    {
        return $this->hasManyThrough(ProductFeatureValue::class, ProductFeature::class)->withTrashedParents();
    }
}
