<?php

use App\Http\Controllers\BarajaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BarajaController::class, 'index'])->name('partida.index');
Route::post('/intento', [BarajaController::class, 'intento'])->name('partida.intento');
Route::post('/reset', [BarajaController::class, 'reset'])->name('partida.reset');


