@extends('layouts.empty', ['paceTop' => true])

@section('title', 'Login Page')

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
        <form method="POST" action="{{ route('login') }}" class="margin-bottom-0">
            @csrf
            <div class="form-group m-b-20">
                <label for="email" class="col-md-12 col-form-label ">{{ __('Correo electrónico') }}</label>
                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Correo electrónico') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group m-b-20">
                <label for="password" class="col-md-12 col-form-label">{{ __('Contraseña') }}</label>
                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="{{ __('Contraseña') }}" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="checkbox checkbox-css m-b-20">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Recordarme') }}
                </label>
            </div>
            <div class="login-buttons">
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Ingresar') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link col-md-12 col-form-label text-md-right" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
                @endif
            </div>
            <div class="m-t-20 text-md-center">  
              
            </div>
        </form>
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->
@endsection
