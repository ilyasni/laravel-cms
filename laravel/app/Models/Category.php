<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'sort_order',
        'menu',
        'is_enabled'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class)->with('categories');
    }

    public function seo()
    {
        return $this->morphMany(Seo::class, 'seoble');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
