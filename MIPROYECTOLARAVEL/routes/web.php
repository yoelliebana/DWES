<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hola mundo';
});

Route::get('ruta1/{id?}', function ($id = 0) {
    return 'Esto es la ruta 1. El usuario es: ' . $id;
})
->where('id', '[0-9]+');

Route::get('ruta2', function () {
    return 'Esto es la ruta 2';
});

// Si hay varios parámetros podemos validarlos usando un array:
Route::get('user/{id}/{name}', function($id, $name)
{
//
})
->where(array('id' => '[0-9]+', 'name' => '[A-Za-z]+'));
