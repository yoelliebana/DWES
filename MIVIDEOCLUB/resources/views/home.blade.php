@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Pantalla de inicio') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Te has logeado correctamente') }}
                    <br><br>
                    <a href="{{ url('/catalog') }}" class="btn btn-primary">
                        {{ __('Ir al catálogo') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
