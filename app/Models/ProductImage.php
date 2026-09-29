<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


class ProductImage extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function product(){
        return $this->belongsTo(Product::class);
    }

    protected function name(): Attribute{
        return Attribute::make(
            get: fn (string $value) => asset("storage/images/product/$value")
        );
    }

}
