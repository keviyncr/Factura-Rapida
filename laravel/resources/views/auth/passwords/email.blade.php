@extends('layouts.empty', ['paceTop' => true])

@section('title', 'Restaurar contraseña')

@section('content')
<!-- begin login-cover -->
<div class="login-cover">
    <div class="login-cover-image" style="background-image: url({{ asset('frontend/img/fondoFR.png') }})" data-id="login-cover-image"></div>
    <div class="login-cover-bg"></div>
</div>
<!-- end login-cover -->

<!-- begin login -->
<div class="login login-v2" data-pageload-addclass="animated fadeIn">
    <!-- begin brand -->
    <div class="login-header">
        <div class="brand">
            <span class=""><img src="{{ asset('frontend/img/logoFR-2.png') }}" width="300" alt="Factura Rapida"></span> 
            <small>Ingrese el correo electronico para restaurar su contraseña</small>
        </div>
        <div class="icon">
            <i class="fa fa-lock"></i>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin login-content -->
    <div class="login-content">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group m-b-20">
                <label for="email" class="col-md-12 col-form-label ">{{ __('Correo electrónico') }}</label>

                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Correo electrónico') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group m-b-20">
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Enviar nuevo enlace') }}
                </button>
            </div>
            <div class="m-t-20 text-md-center">                
            Regresar al inicio de sesión? Click <a href="{{ route('login') }}">aquí</a>.
            </div>
        </form>
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->
@endsection
