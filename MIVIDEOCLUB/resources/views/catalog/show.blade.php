@extends('layouts.master')
@section('content')
<div class=row>
    <div class="col-sm-4">
        <img src="{{$movies['poster']}}" style="height:300px" alt="Poster de la película">
    </div>

    <div class="col-sm-8">
        <h2>{{$movies['title']}}</h2>
        <p><strong>Año:</strong> {{$movies['year']}}</p>
        <p><strong>Director:</strong> {{$movies['director']}}</p>
        <p><strong>Sinopsis:</strong> {{$movies['synopsis']}}</p>

        <a href="{{ url('/catalog/edit/' . $movies->id) }}" class="btn btn-warning mt-3">Editar película</a>
        <a href="{{ url('/catalog/') }}" class="btn btn-primary mt-3">Volver al listado</a>
    </div>
</div>
@endsection