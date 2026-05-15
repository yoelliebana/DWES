<div>
@extends('layouts.master')
@section('content')
<div class="container">
        <h2>Crear Pelicula</h2>

        <form action="{{ url('catalog/create') }}" method="POST"> <!-- Envia a la ruta de web -->
            @csrf <!-- Obligatorio -->
            <div class="form-group">
                <label for="title">Titulo:</label>
                <input type="text" name="title">
            </div>

            <div class="form-group">
                <label for="year">Año:</label>
                <input type="number" name="year">                
            </div>

            <div class="form-group">
                <label for="director">Director:</label>
                <input type="text" name="director">
            </div>

            <div class="form-group">
                <label for="poster">Poster:</label>
                <input type="text" name="poster">
            </div>

            <div class="form-group">
                <label for="rented">Alquilada:</label>
                <select name="rented">
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="synopsis">Synopsis:</label>
                <input type="textarea" name="synopsis"> 
            </div>
            <button type="submit">Guardar</button>
        </form>
    </div>
@endesction
</div>