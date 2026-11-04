<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Product\BestSellerController;
use App\Http\Controllers\Api\Product\NewestController;

Route::controller(CategoryController::class)->group(function(){

    Route::get('category', 'index');
    Route::get('category/{id}', 'show');

});

Route::get('products/bestseller', BestSellerController::class);
Route::get('products/newest', NewestController::class);