@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifica tu correo electrónico') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un correo de confirmación a la dirección proporcionada.') }}
                        </div>
                    @endif

                    {{ __('Antes de seguir, por favor, revise el correo de verificación. Encontrará un link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Enviar otro correo.') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
