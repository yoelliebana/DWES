@extends('layouts.master')
@section('content')
<h1>Contador</h1>

    <div>

        <!-- Incrementar -->
        <form method="POST" action="{{ url('/sesion/increment') }}">
            @csrf
            <button class="btn btn-success btn-lg">+</button>
        </form>

        <h2>{{ $contador }}</h2>

        <!-- Decrementar -->
        <form method="POST" action="{{ url('/sesion/decrement') }}">
            @csrf
            <button class="btn btn-danger btn-lg">-</button>
        </form>

    </div>

    <!-- Reset -->
    <form method="POST" action="{{ url('sesion/reset') }}" class="mt-4">
        @csrf
        <button class="btn btn-primary">Limpiar</button>
    </form>

</div>
@endsection