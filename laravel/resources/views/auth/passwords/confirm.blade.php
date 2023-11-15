@extends('layouts.empty', ['paceTop' => true])

@section('title', 'Sesion Bloqueada')

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
            <small>{{ __('Por favor confirme su contraseña antes de continuar.') }}</small>
        </div>
        <div class="icon">
            <i class="fa fa-lock"></i>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin login-content -->
    <div class="login-content">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group m-b-20">
                <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group m-b-20">
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link col-md-12 col-form-label text-md-right" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>
        </form>
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->
@endsection

