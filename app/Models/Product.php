<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'size',
        'description',
        'image',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size'  => 'array'
    ];

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        return ("{$this->image}") ? url()->to('/' . "{$this->image}") : '';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
