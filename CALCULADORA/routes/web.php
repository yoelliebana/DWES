<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculadoraController;
//Sesion
use App\Http\Controllers\SesionController;
//Controlador
use App\Http\Controllers\AhorcadoController;

Route::get('/',           [CalculadoraController::class, 'index']    )->name('calculadora');
Route::post('/digito',    [CalculadoraController::class, 'digito']   )->name('digito');
Route::post('/operacion', [CalculadoraController::class, 'operacion'])->name('operacion');
Route::post('/calcular',  [CalculadoraController::class, 'calcular'] )->name('calcular');
Route::post('/unaria',    [CalculadoraController::class, 'unaria']   )->name('unaria');
Route::post('/activar',   [CalculadoraController::class, 'activar']  )->name('activar');
Route::post('/borrar',    [CalculadoraController::class, 'borrar']   )->name('borrar');
Route::post('/limpiar',   [CalculadoraController::class, 'limpiar']  )->name('limpiar');

//Sesion
Route::get('/sesion', [SesionController::class, 'index']);
Route::post('/sesion/increment', [SesionController::class, 'increment']);
Route::post('/sesion/decrement', [SesionController::class, 'decrement']);
Route::post('/sesion/reset', [SesionController::class, 'reset']);

//Ahorcado
Route::get( '/ahorcado',        [AhorcadoController::class, 'index'] )->name('ahorcado');
Route::post('/ahorcado/setup',  [AhorcadoController::class, 'setup'] )->name('ahorcado.setup');
Route::get( '/ahorcado/jugar',  [AhorcadoController::class, 'jugar'] )->name('ahorcado.jugar');
Route::post('/ahorcado/letra',  [AhorcadoController::class, 'letra'] )->name('ahorcado.letra');
Route::post('/ahorcado/nueva',  [AhorcadoController::class, 'nueva'] )->name('ahorcado.nueva');