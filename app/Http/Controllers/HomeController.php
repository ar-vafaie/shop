<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index(){
        $data = Product::with('images')->orderByDesc('buy_count')->take(10)->get();
        return view('index', $data);
    }
}
