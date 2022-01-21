<?php

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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function(){
    // resource routes
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class);
    Route::resource('penjualan', \App\Http\Controllers\PenjualanController::class);
    Route::resource('pembelian', \App\Http\Controllers\PembelianController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // api routes
    Route::get('api/products', [\App\Http\Controllers\ApiController::class, 'getProducts']);
    Route::get('api/categories', [\App\Http\Controllers\ApiController::class, 'getCategories']);
    Route::get('api/warehouses', [\App\Http\Controllers\ApiController::class, 'getWarehouses']);
    Route::get('api/transactions', [\App\Http\Controllers\ApiController::class, 'getTransactions']);
    Route::get('api/suppliers', [\App\Http\Controllers\ApiController::class, 'getSuppliers']);
    Route::get('api/users', [\App\Http\Controllers\ApiController::class, 'getUsers']);
    Route::get('get/product/{id}', [\App\Http\Controllers\ApiController::class, 'getProduct']);

    Route::get('get/penjualan', [\App\Http\Controllers\PenjualanController::class, 'getPenjualan']);
    Route::post('payment', [\App\Http\Controllers\PenjualanController::class, 'payment']);
    Route::post('payment/pembelian/{id}', [\App\Http\Controllers\PembelianController::class, 'payment']);

});

