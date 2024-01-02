<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ProductModel extends Model
{
    use AsSource;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_model_product', 'product_model_id', 'product_id');
    }
}
