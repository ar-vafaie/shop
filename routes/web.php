<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ProductController as ProductControllerApi;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/auth', [UserController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [UserController::class, 'login'])->middleware('guest')->name('login.post');
Route::post('/register', [UserController::class, 'register'])->middleware('guest')->name('register.post');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');


Route::get('/categories', [ProductController::class, 'categories'])->name('categories');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/product/{productId}', [ProductController::class, 'product'])->name('product');


Route::middleware(['auth:admin'])->group( function(){
    Route::prefix('/admin')->group( function(){
        /*********************** page's scope *****************************/
        Route::controller(AdminPanelController::class)->group( function(){

            Route::get('', 'index')->name('admin');
            Route::get('/add-product', 'addProduct')->name('add.product');
            Route::get('/add-category', 'addCategory')->name('add.category');
            
            Route::get('/manage-admins', 'manageAdmins')->name('manage.admins');
            });

        /*============================ api scope =======================*/ 
    Route::prefix('/api')->group(function(){
        Route::controller(ProductControllerApi::class)->group(function(){
            Route::post('/products', 'store')->name('store.product');     

        });
        Route::controller(CategoryController::class)->group(function(){
            Route::post('/categories', 'store')->name('store.category');

        });
        Route::controller(AdminController::class)->group(function(){
            Route::get('/admin', 'index');

        });
    });
    });
});








