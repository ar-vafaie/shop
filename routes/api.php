<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;


Route::controller(CategoryController::class)->group(function(){

    Route::get('category', 'index');
    Route::get('category/{id}', 'show');

});

