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
                   <form name="f1" id="f1" action="#" method="post" class="alignet-form-vpos2">
                    
                        <!-- datos ocultos -->
                        <input hidden="true" type="text" name ="acquirerId" value="{{ $acquirerId }}" />
                        <input hidden="true" type="text" name ="idCommerce" value="{{ $idCommerce }}" />
                        <input hidden="true" type="text" name ="purchaseOperationNumber" id ="purchaseOperationNumber" value="{{ $purchaseOperationNumber }}"/>
                        <input hidden="true" type="text" name ="purchaseAmount" id ="purchaseAmount" value="{{ $totalt }}"/>
                        <input hidden="true" type="text" name ="purchaseCurrencyCode" value="188" />
                        <input hidden="true" type="text" name="language" value="SP" />
                        <input hidden="true" type="text" name ="shippingFirstName" value="{{ $shippingFirstName }}" />
                        <input hidden="true" type="text" name ="shippingLastName" value="{{ $shippingLastName }}" />
                        <input hidden="true" type="text" name ="shippingEmail" value="{{ $shippingEmail }}" />
                        <input hidden="true" type="text" name ="shippingAddress" value="Cartago" />
                        <input hidden="true" type="text" name ="shippingZIP" value="30506" />
                        <input hidden="true" type="text" name ="shippingCity" value="Paraiso" />
                        <input hidden="true" type="text" name ="shippingState" value="Cartago" />
                        <input hidden="true" type="text" name ="shippingCountry" value="CR" />
                        <input hidden="true" type="text" name ="userCommerce" value="CONTAFAST1" />
                        <input hidden="true" type="text" name ="userCodePayme" value="CONTAFAST1" />
                        <input hidden="true" type="text" name ="programmingLanguage" value="PHP" />
                        <input hidden="true" type="text" name ="reserved1"  id ="reserved1" value="{{ $plan }}" />
                        <input hidden="true" type="text" name ="reserved2" id ="reserved2" value="{{ $type }}" />
                        <input hidden="true" type="text" name ="reserved3"  id ="reserved1" value="{{ $idCard }}" />
                        <input hidden="true" type="text" name ="reserved4" id ="reserved2" value="{{ $typeIdCard }}" />
                        <input hidden="true" type="text" name ="purchaseVerification" value="{{ $purchaseVerification }}" />
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
                                    <div class="step active">
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
                        <!-- BEGIN checkout-body -->
                        <div class="checkout-body">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-cart">
                                    <thead>
                                        <tr>
                                            <th>Nombre del Producto</th> 
                                            <th class="text-center">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="cart-product">  
                                              @if($plan == "1")
                                                <div class="product-info">
                                                     @if($type == 1) 
                                                     <input hidden="true" type="text" name ="descriptionProducts" value="Plan postpago mensual" />
                                                      Plan postpago mensual
                                                     @endif
                                                     @if($type == 2) 
                                                     <input hidden="true" type="text" name ="descriptionProducts" value="Plan postpago anual" />
                                                      Plan postpago anual
                                                     @endif
                                                </div>
                                                @else
                                                    <div class="product-info">
                                                    @if($type == 1) 
                                                    <input hidden="true" type="text" with="100%" name ="descriptionProducts" value="Plan prepago 20 documentos" />
                                                        Plan prepago 20 documentos
                                                    @endif
                                                    @if($type == 2) 
                                                    <input hidden="true" type="text" with="100%" name ="descriptionProducts" value="Plan prepago 100 documentos" />
                                                         Plan prepago 100 documentos
                                                    @endif
                                                    @if($type == 3) 
                                                    <input hidden="true" type="text" name ="descriptionProducts" value="Plan prepago 1000 documentos" />
                                                       Plan prepago 1000 documentos  
                                                    @endif
                                                </div>
                                                @endif
                                                
                                            </td> 
                                    
                                    <td class="cart-total text-center" id="total_lineL">
                                        {{ $sub }}
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="cart-summary" colspan="4">
                                            <div class="summary-container">
                                                <div class="summary-row">
                                                    <div class="field">Subtotal</div>                                                            
                                                    <div class="value" id="sub_total">{{ $sub }}</div>
                                                </div>
                                                <div class="summary-row text-danger">
                                                    <div class="field">IVA</div>
                                                    <div class="value" id="iva">{{ $iva }}</div>
                                                </div>
                                                <div class="summary-row total">
                                                    <div class="field">Total</div>
                                                   
                                                    <div class="value" id="totalL">{{ $total }}</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </div>
                        <!-- END checkout-body -->
                        <!-- BEGIN checkout-footer -->
                        <div class="checkout-footer">
                            <input type="button" class="btn btn-inverse btn-lg btn-theme width-200" onclick="pay()" value="Comprar">
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