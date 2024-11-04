<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;


Route::get('/', function () {
    return view('home');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

Route::get('/productos/registrar', [ProductoController::class, 'create'])->name('productos.create');

Route::post('/productos/registrar', [ProductoController::class, 'store'])->name('productos.store');
