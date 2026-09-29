<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

Route::prefix('/admin')->group( function(){
    Route::controller(AdminPanelController::class)->group( function(){
        Route::middleware(['auth:admin'])->group( function(){

            Route::get('', 'index')->name('admin');
            Route::get('/add-product', 'addProduct')->name('add.product');
            Route::post('/products', 'storeProduct')->name('admin.products.store');
            Route::get('/add-category', 'addCategory')->name('add.category');
            Route::post('/categories', 'storeCategory')->name('admin.category.store');

            Route::get('/manage-admins', 'manageAdmins')->name('manage.admins');
        });
    });
});






// APIs

Route::get('/api/categories', [ProductController::class, 'categoriesApi']);
Route::get('/api/categories/{categoryId}', [ProductController::class, 'categoryProducts']);

Route::get('/api/products/bestseller', [ProductController::class, 'bestseller']);



