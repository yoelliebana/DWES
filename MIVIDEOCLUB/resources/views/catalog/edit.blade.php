<div>
@extends('layouts.master')
@section('content')Pantalla principal
<div class="container">
    <h2>Editar película</h2>

    <form method="POST" action="{{ url('/catalog/update/' . $movies->id) }}">
        
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="title" class="form-control" value="{{ $movies->title }}">
        </div>

        <div class="form-group">
            <label>Año</label>
            <input type="text" name="year" class="form-control" value="{{ $movies->year }}">
        </div>

        <div class="form-group">
            <label>Director</label>
            <input type="text" name="director" class="form-control" value="{{ $movies->director }}">
        </div>

        <div class="form-group">
            <label>Poster</label>
            <input type="text" name="poster" class="form-control" value="{{ $movies->poster }}">
        </div>

        <div class="form-group">
            <label>Sinopsis</label>
            <textarea name="synopsis" class="form-control">{{ $movies->synopsis }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">
            Guardar cambios
        </button>

    </form>
</div>

@stop
</div>