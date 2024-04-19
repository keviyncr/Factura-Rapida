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
            <small>Inicie sesión para usar el sistema</small>
        </div>
        <div class="icon"> 
            <i class="fa fa-lock"></i>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin login-content -->
    <div class="login-content">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group m-b-1">
                <label for="email" class="col-md-12 col-form-label ">{{ __('Correo electrónico') }}</label>

                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Correo electrónico') }}" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group m-b-1">
                <label for="password" class="col-md-12 col-form-label">{{ __('Contraseña') }}</label>

                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Contraseña') }}" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group m-b-20">
                <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirmar Contraseña') }}</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirmar Contraseña') }}" required autocomplete="new-password">
            </div>

            <div class="form-group m-b-20">
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Restaurar Contraseña') }}
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
