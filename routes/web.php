<?php


use App\Http\Controllers\OpenFoodFactsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OpenFoodFactsController::class, 'index'])->name('products.search');
Route::post('/fetch', [OpenFoodFactsController::class, 'fetch'])->name('products.fetch');
Route::get('/statistics', [OpenFoodFactsController::class, 'statistics'])->name('statistics');