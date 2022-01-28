<?php

use App\Models\Sale;
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

Auth::routes([
    'regiter' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function(){
    // resource routes
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
    Route::resource('roles-permissions', \App\Http\Controllers\RolePermissionController::class);

    // datatable routes
    Route::get('datatable/users', [\App\Http\Controllers\DataTablesController::class, 'getUsers']);
    Route::get('datatable/sales', [\App\Http\Controllers\DataTablesController::class, 'getSales']);
    Route::get('datatable/payments', [\App\Http\Controllers\DataTablesController::class, 'getPayments']);
    Route::get('datatable/products', [\App\Http\Controllers\DataTablesController::class, 'getProducts']);
    Route::get('datatable/suppliers', [\App\Http\Controllers\DataTablesController::class, 'getSuppliers']);
    Route::get('datatable/purchases', [\App\Http\Controllers\DataTablesController::class, 'getPurchases']);
    Route::get('datatable/activities', [\App\Http\Controllers\DataTablesController::class, 'getActivities']);
    Route::get('datatable/categories', [\App\Http\Controllers\DataTablesController::class, 'getCategories']);
    Route::get('datatable/warehouses', [\App\Http\Controllers\DataTablesController::class, 'getWarehouses']);
    Route::get('datatable/roles-permissions', [\App\Http\Controllers\DataTablesController::class, 'getRoles']);

    // additional routes
    Route::get('get/sale/{id}', [\App\Http\Controllers\SaleController::class, 'getSale']);
    Route::get('get/purchase/{id}', [\App\Http\Controllers\PurchaseController::class, 'getPurchase']);
    Route::put('password/reset/manual', [\App\Http\Controllers\UserController::class, 'resetPassword']);
    Route::get('activities', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
    Route::get('get/permission/{id}', [\App\Http\Controllers\RolePermissionController::class, 'getPermission']);
    Route::get('get/user/{id}', [\App\Http\Controllers\RolePermissionController::class, 'getUserRole']);
    Route::post('roles-permissions/setup-permissions/{id}', [\App\Http\Controllers\RolePermissionController::class, 'updatePermission']);

    // report route
    Route::get('report/sales', [\App\Http\Controllers\ReportController::class, 'salesReport'])->name('reports.sales.index');
    Route::get('get/report/all', [\App\Http\Controllers\ReportController::class, 'getSalesAll']);
    Route::get('get/report/product', [\App\Http\Controllers\ReportController::class, 'getSalesProduct']);
    Route::get('report/purchases', [\App\Http\Controllers\ReportController::class, 'reportPurchasePage']);
    Route::get('report/purchases/filter', [\App\Http\Controllers\ReportController::class, 'getReportPurchases']);

});

