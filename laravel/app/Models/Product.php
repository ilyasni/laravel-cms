<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'is_enabled'
    ];

    public function seo()
    {
        return $this->morphMany(Seo::class, 'seoble');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function models()
    {
        return $this->belongsToMany(ProductModel::class, 'product_model_product', 'product_id', 'product_model_id');
    }

    public function productPositions()
    {
        return $this->hasMany(ProductPosition::class);
    }
}
