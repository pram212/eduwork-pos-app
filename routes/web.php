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
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    Route::put('password/reset/manual', [\App\Http\Controllers\UserController::class, 'resetPassword']);
    Route::get('activities', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');

    // api routes
    Route::get('api/products', [\App\Http\Controllers\ApiController::class, 'getProducts']);
    Route::get('api/categories', [\App\Http\Controllers\ApiController::class, 'getCategories']);
    Route::get('api/warehouses', [\App\Http\Controllers\ApiController::class, 'getWarehouses']);
    Route::get('api/transactions', [\App\Http\Controllers\ApiController::class, 'getTransactions']);
    Route::get('api/suppliers', [\App\Http\Controllers\ApiController::class, 'getSuppliers']);
    Route::get('api/users', [\App\Http\Controllers\ApiController::class, 'getUsers']);
    Route::get('datatable/activities', [\App\Http\Controllers\DataTablesController::class, 'getActivities']);
    Route::get('datatable/sales', [\App\Http\Controllers\DataTablesController::class, 'getSales']);
    Route::get('datatable/purchases', [\App\Http\Controllers\DataTablesController::class, 'getPurchases']);
    Route::get('get/sale/{id}', [\App\Http\Controllers\SaleController::class, 'getSale']);
    Route::get('get/purchase/{id}', [\App\Http\Controllers\PurchaseController::class, 'getPurchase']);
    Route::post('pay/purchase/{id}', [\App\Http\Controllers\PurchaseController::class, 'payment']);

});

