@extends('layouts.empty', ['paceTop' => true])

@section('title', 'Registro')

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
            <small>Ingrese los datos necesarios para su registro</small>
        </div>
        <div class="icon">
            <i class="fa fa-lock"></i>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin login-content -->
    <div class="login-content">

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group m-b-1">
                <label for="name" class="col-md-12 col-form-label ">{{ __('Nombre') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="{{ __('Nombre') }}" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group m-b-1">
                <label for="email" class="col-md-12 col-form-label ">{{ __('Correo electrónico') }}</label>

                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Correo electrónico') }}" value="{{ old('email') }}" required autocomplete="email">

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
            <div class="form-group m-b-1">
                <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirmar contraseña') }}</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirmar contraseña') }}" required autocomplete="new-password">
            </div>
            <div class="form-group m-b-20">
                <label for="name" class="col-md-12 col-form-label ">{{ __('Codigo de verificación') }}</label>

                <input id="code_c" type="text" class="form-control @error('code_c') is-invalid @enderror" name="code_c" placeholder="{{ __('Codigo de verificación') }}" value="{{ old('code_c') }}" required autocomplete="Codigo de verificación" autofocus>
                 @error('code_c')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
               @enderror
            </div>

            <div class="form-group m-b-20">
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Registrar') }}
                </button>
            </div>
            <div class="m-t-20 text-md-center">
                Regresar al inicio de sesión?  <a href="{{ route('login') }}">Click aquí</a>.
            </div>
        </form>
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->
@endsection

