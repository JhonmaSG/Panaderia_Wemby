<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;

Route::get('/', function () {
    return view('home');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//sprint inventario

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

Route::get('/productos/registrar', [ProductoController::class, 'create'])->name('productos.create');

Route::post('/productos/registrar', [ProductoController::class, 'store'])->name('productos.store');

Route::get('/productos/{id}/editar-stock', [ProductoController::class, 'editStock'])->name('productos.edit-stock');

Route::post('/productos/{id}/actualizar-stock', [ProductoController::class, 'updateStock'])->name('productos.update-stock');

Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');


//sprint ventas

Route::get('/ventas/crear-venta', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas/crear-venta', [VentaController::class, 'store'])->name('ventas.store');
