<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ProductPosition extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'price',
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
