<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'img_path'])]
class Category extends Model
{
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_id',
            'product_id'
        );
    }
    protected function imgPath(): Attribute{
        return Attribute::make(
            set: fn (string $value) => "/storage/images/category/$value"
        );
    }
}

