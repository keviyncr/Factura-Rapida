<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Factura Rapida | @yield('title')</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="KCR Soluciones" />

        <link rel="shortcut icon" href="{{ asset('frontend/img/iconFR.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('frontend/img/iconFR.png') }}" type="image/x-icon">

        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="{{ asset('frontend/css/e-commerce/app.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/css/transparent/theme/black.min.css') }}" rel="stylesheet" />
        <script type="text/javascript" src="https://vpayment.verifika.com/VPOS2/js/modalcomercio.js" ></script>
        <!-- ================== END BASE CSS STYLE ================== -->
    </head>
    <body style="background-image: url({{ asset('frontend/img/fondoFR.png') }}); background-repeat: no-repeat; background-size: cover;">
        <!-- BEGIN #page-container -->
        <div id="page-container" class="fade">
            <!-- BEGIN #top-nav -->
            <div id="top-nav" class="top-nav">
                <!-- BEGIN container -->
                <div class="container">
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <a href="{{ url('/home') }}" class="navbar-brand"><span class=""><img src="{{ asset('frontend/img/logoFR-2.png') }}" width="150" alt="Factura Rapida"></span> </a>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                        </ul>
                    </div>
                </div>
                <!-- END container -->
            </div>
            <!-- END #top-nav -->
            <!-- begin header-nav -->
            <ul class="navbar-nav navbar-right navbar-default"></ul>
            <!-- end header navigation right -->
       
        <!-- end #header -->
        <!-- BEGIN #checkout-cart -->
        <div class="section-container" id="checkout-cart">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN checkout -->
                <div class="checkout">
                        <!-- BEGIN checkout-header -->
                        <div class="checkout-header">
                            <!-- BEGIN row -->
                            <div class="row">
                                
                                 <!-- BEGIN col-3 -->
                                <div class="col-lg-3">
                                    <div class="step">
                                        <a href="#">
                                            <div class="number">1</div>
                                            <div class="info">
                                                <div class="title">Datos para faturación</div>
                                                <div class="desc">Información de la razón social a facturar.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END col-3 -->
                                <!-- BEGIN col-3 -->
                                <div class="col-lg-3">
                                    <div class="step ">
                                        <a href="#">
                                            <div class="number">3</div>
                                            <div class="info">
                                                <div class="title">Información del plan</div>
                                                <div class="desc">Datos del plan seleccionado.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END col-3 -->         
                                <!-- BEGIN col-3 -->
                                <div class="col-lg-3">
                                    <div class="step active">
                                        <a href="#">
                                            <div class="number">4</div>
                                            <div class="info">
                                                <div class="title">Proceso completado</div>
                                                <div class="desc">Respuesta de la solicitud.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END col-3 -->
                            </div>
                            <!-- END row -->
                        </div>
                        <!-- END checkout-header -->
                        @if($authorizationResult == "00")
                        <!-- BEGIN checkout-body -->
                        <div class="checkout-body">
                            <div class="jumbotron m-b-0 text-center">
								<h2 class="display-4">Pago realizado con exito</h2>
								<p class="lead mb-4">Se ha enviado un código de verificación al correo electrónico  para realizar el registro de su usuario y compañia.<br> Favor verificar y realizar el proceso de registro. </p>
								<br>
								<p><a href="{{ url('/register') }}" class="btn btn-primary btn-lg">Registrarse</a></p>
							</div>
                        </div>
                        <!-- END checkout-body -->
                        @else
                        <!-- BEGIN checkout-body -->
                        <div class="checkout-body">
                            <div class="jumbotron m-b-0 text-center">
								<h2 class="display-4">Error al realizar pago</h2>
								<p style="color:#A71515">{{ $errorMessage }}</p>
								<p class="lead mb-4">Se ha producido un error al realizar su pago, por favor intente de nuevo.</p>
								<br>
								<p><a href="{{ url('/') }}" class="btn btn-primary btn-lg">Regresar al inicio</a></p>
							</div>
                        </div>
                        <!-- END checkout-body -->
                        
                        @endif
                        
                </div>
                <!-- END checkout -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #checkout-cart -->
        <!-- BEGIN #footer -->
        <div id="footer" class="footer">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                        <!-- BEGIN policy -->
                        <div class="policy">
                            <div class="policy-icon"><i class="fa fa-check-circle"></i></div>
                            <div class="policy-info">
                                <h4>Realize sus Documentos electrónicos</h4>
                                <p class="text-danger">Factura Rápida ofrece una forma rapida,facil y segura para la realización de sus documentos electrónicos.</p>
                            </div>
                        </div>
                        <!-- END policy -->
                    </div>
                    <!-- END col-4 -->
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4 mb-4 mb-md-0 text-center">
                        <span class=""><img src="{{ asset('frontend/img/logoFR-2.png') }}" width="150" alt="Factura Rapida"></span>
                    </div>
                    <!-- END col-4 -->
                    <!-- BEGIN col-4 -->
                    <div class="col-lg-4 col-md-4">
                        <!-- BEGIN policy -->
                        <div class="policy">
                            <div class="policy-icon"><i class="fa fa-users"></i></div>
                            <div class="policy-info">
                                <h4>Unete a los usuarios</h4>
                                <p class="text-danger">Cientos de usuarios han optado por Factura Rápida como su solución de facturación electrónica.</p>
                            </div>
                        </div>
                        <!-- END policy -->
                    </div>
                    <!-- END col-4 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END container -->
        </div>
        <!-- END #footer -->

    </div>
    <!-- END #page-container -->


    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('frontend/js/e-commerce/app.min.js') }}"></script>
    <script src="{{ asset('admin/js/pay.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
</body>
</html>