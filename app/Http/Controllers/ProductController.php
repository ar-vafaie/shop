<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categories(){
        return view('categories');
    }

    public function bestseller(){
        $products = Product::with(['images' => fn ($query) => $query->limit(1)])->select(['id', 'name', 'price'])->orderByDesc('buy_count')->limit(10)->get();
        return json_encode($products);
    }
    public function newest(){
        $products = Product::with(['images' => fn($query) => $query->limit(1)])->select(['id', 'name', 'price'])->orderByDesc('created_at')->limit(10)->get();
        return json_encode($products);
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

