<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Seo extends Model
{
    use AsSource;

    protected $fillable = [
        'title',
        'description',
        'meta-title',
        'meta-description',
        'meta-keywords',
        'og-type',
        'og-image',
        'og-url',
        'og-site_name',
        'og-locale'
    ];

    protected $table = 'seo';

    public function seoble()
    {
        return $this->morphTo();
    }
}
