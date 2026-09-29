<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function index(){
        // dd(session()->all());
        return view('admin.index');
    }
    public function addProduct(){
        return view('admin.add-product');
    }

    public function storeProduct(Request $request)
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

    public function addCategory(){
        return view('admin.add-catetory');
    }
    public function storeCategory(Request $request){
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

    public function manageAdmins(){
        return view('admin.');
    }
}
