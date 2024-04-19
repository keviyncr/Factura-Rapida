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
            <small>Verificación de correo electrónico</small>
        </div>
        <div class="icon">
            <i class="fa fa-lock"></i>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin login-content -->
    <div class="login-content">
        @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico..') }}
        </div>
        @endif
        <form method="POST" action="{{ route('verification.resend') }}" class="margin-bottom-0">
            @csrf           
            <div class="login-buttons">
                <label for="password" class="col-md-12 col-form-label">{{ __('Para poder continuar, revise su correo electrónico, se ha enviado un enlace de verificación.') }}
                </label>
                <button type="submit" class="btn btn-info btn-block btn-lg">
                    {{ __('Enviar nuevo enlace') }}
                </button>
            </div>
            
        </form>
    </div>
    <!-- end login-content -->
</div>
<!-- end login -->
@endsection
