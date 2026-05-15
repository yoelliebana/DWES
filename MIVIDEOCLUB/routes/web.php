<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\Authenticate;
//Sesion
use App\Http\Controllers\SesionController;

Route::get('/', [HomeController::class, 'index']);

Route::get('login', function () {
    return view('auth.login');
});

Route::get('catalog', [CatalogController::class, 'getIndex'])->middleware('auth');

Route::get('catalog/show/{id}', [CatalogController::class, 'getShow'])
->where('id', '[0-9]+');

Route::get('catalog/create', [CatalogController::class, 'getCreate']);
Route::post('catalog/create', [CatalogController::class, 'postCreate']);

Route::get('catalog/edit/{id?}', [CatalogController::class, 'getEdit'])
->where('id', '[0-9]+');
Route::put('catalog/update/{id}', [CatalogController::class, 'putUpdate']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


//Sesion
Route::get('/sesion', [SesionController::class, 'index']);
Route::post('/sesion/increment', [SesionController::class, 'increment']);
Route::post('/sesion/decrement', [SesionController::class, 'decrement']);
Route::post('/sesion/reset', [SesionController::class, 'reset']);