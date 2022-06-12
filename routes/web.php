<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('clear', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('view:cache');
    return redirect()->route('home');
});

Route::get('migrate', function () {
    \Artisan::call('migrate:fresh --seed');
    return redirect()->route('home');
});


Auth::routes(['verify' => true]);

Route::group([], function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('cart', [CartController::class, 'getCart'])->name('cart');
});

Route::group(['middleware' => ['can:make_orders', 'verified', 'auth']], function () {
    Route::post('orders/form', [OrderController::class, 'form'])->name('orders.form');
    Route::post('orders/done', [OrderController::class, 'createOrder'])->name('orders.post');
});


Route::group(['middleware' => ['can:create_products', 'verified', 'auth']], function () {

    Route::get('home/salesByCategory', [HomeController::class, 'getSalesByCategoryData'])->name('home.salesByCategory');
    Route::get('home/salesByDay', [HomeController::class, 'getSalesByDayData'])->name('home.salesByDay');
    Route::get('home/topSales', [HomeController::class, 'getTopSales'])->name('home.topSales');

    Route::get('users', [UserController::class, 'getDatatable'])->name('users');
    Route::get('users/list', [UserController::class, 'getUsers'])->name('users.list');
    Route::delete('users/delete', [UserController::class, 'deleteUser'])->name('users.delete');

    Route::get('products', function () {
        return view('admin.products.list');
    })->name('products');
    Route::get('products/list', [ProductController::class, 'getProducts'])->name('products.list');
    Route::get('products/create', [ProductController::class, 'formCreateProduct'])->name('products.create');
    Route::get('products/edit/{product}', [ProductController::class, 'formEditProduct'])->name('products.edit');
    Route::post('products/post', [ProductController::class, 'createProduct'])->name('products.post');
    Route::post('products/change', [ProductController::class, 'editProduct'])->name('products.change');
    Route::delete('products/delete', [ProductController::class, 'deleteProduct'])->name('products.delete');



    Route::get('orders', function () {
        return view('admin.orders.list');
    })->name('orders');
    Route::post('orders/list', [OrderController::class, 'getOrders'])->name('orders.list');

});

Route::group(['middleware' => ['can:create_admins', 'verified', 'auth']], function () {

    Route::patch('users/upgrade', [UserController::class, 'changePowerUser'])->name('users.upgrade');
    Route::patch('users/restore', [UserController::class, 'restoreUser'])->name('users.restore');
    Route::delete('users/force_delete', [UserController::class, 'forceDeleteUser'])->name('users.force_delete');

    Route::post('product_categories/post', [ProductCategoryController::class, 'createCategory'])->name('product_categories.post');
    Route::delete('product_categories/delete', [ProductCategoryController::class, 'deleteCategory'])->name('product_categories.delete');


});


