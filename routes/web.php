<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::resource('products', ProductController::class);
// Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
