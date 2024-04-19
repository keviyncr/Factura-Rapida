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
        <script type="text/javascript" src="https://integracion.alignetsac.com/VPOS2/js/modalcomercio.js" ></script>
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
                    <form name="f1" id="f1" action="#" method="post" class="alignet-form-vpos2" >
                        @csrf
                        <input hidden="true" type="text" name="plan" value="{{ $plan }}"/>
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
                                            <div class="number">2</div>
                                            <div class="info">
                                                <div class="title">Datos de Usuario</div>
                                                <div class="desc">Acceso al sistema.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- END col-3 -->
                                <!-- BEGIN col-3 -->
                                <div class="col-lg-3">
                                    <div class="step active">
                                        <a href="#">
                                            <div class="number">3</div>
                                            <div class="info">
                                                <div class="title">Realize su pago</div>
                                                <div class="desc">Datos de metodo de pago. </div>
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
                        <!-- datos ocultos -->
                        <input hidden="true" type="text" name ="acquirerId" value="{{ $acquirerId }}" />
                        <input hidden="true" type="text" name ="idCommerce" value="{{ $idCommerce }}" />
                        <input hidden="true" type="text" name ="purchaseOperationNumber" value="{{ $purchaseOperationNumber }}" />
                        <input hidden="true" type="text" name ="purchaseAmount" value="{{ $total }}" />
                        <input hidden="true" type="text" name ="purchaseCurrencyCode" value="188" />
                        <input hidden="true" type="text" name="language" value="SP" />
                        <input hidden="true" type="text" name ="shippingEmail" value="{{ $email }}" />
                        <input hidden="true" type="text" name ="userCommerce" value="CONTAFAST" />
                        <input hidden="true" type="text" name ="userCodePayme" value="CONTAFAST" />
                        <input hidden="true" type="text" name ="descriptionProducts" value="{{ $description }}" />
                        <input hidden="true" type="text" name ="programmingLanguage" value="PHP" />
                        <input hidden="true" type="text" name ="reserved1" value="Compra de plan" />
                        <input hidden="true" type="text" name ="purchaseVerification" value="{{ $purchaseVerification }}" />
                        <input hidden="true" type="text" name ="shippingAddress" value="Cartago" />
                        <input hidden="true" type="text" name ="shippingZIP" value="30506" />
                        <input hidden="true" type="text" name ="shippingCity" value="Paraiso" />
                        <input hidden="true" type="text" name ="shippingState" value="Cartago" />
                        <input hidden="true" type="text" name ="shippingCountry" value="CR" />
                        <!-- BEGIN checkout-body -->
                        <!-- BEGIN checkout-body -->
                        <div class="checkout-body">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-lg-right">Nombre propietario <span class="text-danger">*</span></label>
                                <div class="col-md-2">
                                    <input type="text" required class="form-control required" name="shippingFirstName" placeholder="Nombre" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" required class="form-control required" name="shippingLastName" placeholder="Apellidos" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-lg-right">Numero de tarjeta <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" required class="form-control required" name="cardnumber" placeholder="" />                                    
                                </div>
                                <div class="col-md-3">
                                    <ul class="list-inline payment-type">
                                        <li><a href="#" data-click="set-payment" data-value="Visa" data-toggle="tooltip" data-title="Visa" data-placement="top" data-trigger="hover"><i class="fab fa-cc-visa"></i></a></li>
                                        <li><a href="#" data-click="set-payment" data-value="Master Card" data-toggle="tooltip" data-title="Master Card" data-placement="top" data-trigger="hover"><i class="fab fa-cc-mastercard"></i></a></li>
                                    </ul>
                                    <input type="hidden" name="payment_type" value="" data-id="payment-type" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-lg-right">Fecha Expiración <span class="text-danger">*</span></label>
                                <div class="col-md-2">
                                    <input type="text" required name="mm" placeholder="MM/AA" class="form-control required p-l-5 p-r-5 text-center" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-lg-right">CSC <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="width-100 pull-left m-r-10">
                                        <input type="text" name="number" placeholder="" class="form-control required p-l-5 p-r-5 text-center" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END checkout-body -->
                        <!-- BEGIN checkout-footer -->
                        <div class="checkout-footer">
                            <input type="button" class="btn btn-inverse btn-lg btn-theme width-200" onclick="javascript:AlignetVPOS2.openModal('https://integracion.alignetsac.com/')" value="PAGAR">
                        </div>
                        <!-- END checkout-footer -->
                    </form>
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