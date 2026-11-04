<?php

namespace App\Http\Controllers;
use App\Models\Product;


class ProductController extends Controller
{
    public function categories(){
        return view('categories');
    }

    public function product(int $id)
    {
        $product = Product::with(['images', 'comments', 'categories'])->findOrFail($id);

        $related = Product::whereHas('categories', function ($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->with('images')
            ->limit(4)
            ->get();

        return view('product', compact('product', 'related'));
    }

}

