<?php

use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\GraficosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/reports', [GraficosController::class, 'ventas'])->name('graficos.ventas');
Route::get('/analisis', [AnalisisController::class, 'showAnalysisForm'])->name('analisis.form');
Route::post('/analisis/generar', [AnalisisController::class, 'generateAnalysis'])->name('analisis.generate');