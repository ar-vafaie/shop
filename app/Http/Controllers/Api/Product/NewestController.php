<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;

class NewestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $products = Product::with(['images' => fn($query) => $query->limit(1)])->select(['id', 'name', 'price'])->orderByDesc('created_at')->limit(10)->get();
        return json_encode($products);
    }
}
