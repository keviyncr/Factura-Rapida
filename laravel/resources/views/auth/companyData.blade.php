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
            <ul class="navbar-nav navbar-right navbar-default">

            </ul>
            <!-- end header navigation right -->
        </div>
        <!-- end #header -->
        <!-- BEGIN #checkout-cart -->
        <div class="section-container" id="checkout-cart">
            <!-- BEGIN container -->
            <div class="container">
                <!-- BEGIN checkout -->
                <div class="checkout">
                    <form action="{{ url('purchaseOptions') }}" method="POST" >
                    @csrf
                        <!-- BEGIN checkout-header -->
                        <div class="checkout-header">
                            <!-- BEGIN row -->
                            <div class="row">
                                
                                <!-- BEGIN col-3 -->
                                <div class="col-lg-3">
                                    <div class="step active">
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
                                    <div class="step">
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
                        <!-- begin row -->
                        <div class="row" style="margin-top: 2%">
                            <!-- begin col-8 -->
                            <div class="col-xl-8 offset-xl-2">
<br>
                                <div class="form-group row m-b-10">
                                    <label class="col-lg-3 col-form-label">Nombre completo o razón social: <span class="text-danger"></span></label>
                                    <div class="col-lg-9 col-xl-9">
                                        <input type="text" name="name_company"  @error('name_company') is-invalid @enderror" data-parsley-group="step-2" required class="form-control" placeholder="Nombre de la compañia"/>
                                    </div>
                                </div>
                                <!-- end form-group -->
                                <div class="form-group row m-b-10">
                                    <label class="col-lg-3 col-form-label">Identifiacion: <span class="text-danger"></span></label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="row row-space-6">
                                            <div class="col-6">
                                                <input data-toggle="number"  data-placement="after" class="form-control" type="number"  name="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12" data-parsley-group="step-2" data-parsley-required="true" required/>
                                            </div>
                                            <div class="col-6">
                                                <select class="form-control" data-parsley-required="true" name="type_id_card" id="select-required">
                                                    <option style="color: black;" value="" disabled selected="true">Tipo identifiación</option>
                                                    @foreach($type_id_cards as $type_id_card)
                                                    <option style="color: black;" @if($type_id_card->id ==1)selected @endif  value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group row m-b-10">
                                    <label for="email" class="col-md-3 col-form-label ">{{ __('Correo electrónico') }}</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <input id="email" type="email"  class="form-control " name="shippingEmail" placeholder="{{ __('Correo electrónico') }}" value="{{ old('email') }}" required autocomplete="email">
                                    </div>
                                </div>
<br>
<br>
                                <div class="m-t-20 text-md-center">
                                </div>
                            </div>
                            <!-- end col-8 -->
                        </div>
                        <!-- end row -->
                        <!-- BEGIN checkout-footer -->
                        <div class="checkout-footer">
                            <input type="submit" class="btn btn-inverse btn-lg btn-theme width-200" value="CONTINUAR">
                        </div>
                        <!-- END checkout-footer -->

                    </form>
                    @if(count($errors)>0)
                    @foreach($errors->all as $error)
                    {{ $error }}
                    @endforeach
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
        <!-- BEGIN #footer-copyright -->
        <div id="footer-copyright" class="footer-copyright text-center">
            <br>
        </div>
        <!-- END #footer-copyright -->

    </div>
    <!-- END #page-container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('frontend/js/e-commerce/app.min.js') }}"></script>
    <script src="{{ asset('admin/js/pay.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
</body>
</html>