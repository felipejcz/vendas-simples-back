<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customers/create', [App\Http\Controllers\CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customers/edit/{id}', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customers/edit', [App\Http\Controllers\CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customers/delete/{id}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('customer.destroy');

    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');
    Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
    Route::post('/products/create', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
    Route::get('/products/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
    Route::post('/products/edit', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
    Route::get('/products/delete/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
    Route::post('/orders/create', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
    Route::get('/orders/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
    Route::post('/orders/edit', [App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
    Route::get('/orders/delete/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('order.destroy');
});
