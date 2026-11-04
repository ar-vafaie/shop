<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'images'       => 'required|array|min:1',
            'images.*'     => 'image|max:4096',
            'categories'   => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product = Product::create([
            'name'        => $data['name'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'buy_count'   => $data['buy_count'] ?? 0,
            'description' => $data['description'] ?? '',
        ]);

        // ذخیره چند عکس
        foreach ($request->file('images', []) as $file) {
            $path = $file->store('images/product', 'root');
            $product->images()->create([
                'name' => basename($path),
            ]);
        }

        $product->categories()->sync($data['categories']);

        return redirect()->route('add.product')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
