<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::select('id', 'name', 'img_path')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|max:4096'
        ]);

        $file = $request->file('image')->store('images/category', 'root');
        Category::create([
            'name' => $data['name'],
            'img_path' => basename($file)
        ]);

        return redirect()->route('add.category')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $products = Product::whereHas('categories', function ($query) use ($id) {
        $query->where('category_id', $id);
    })->with(['images' => function ($query) {$query->limit(1);}])
    ->select(['id','name', 'price'])
    ->get();
        return $products;
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
