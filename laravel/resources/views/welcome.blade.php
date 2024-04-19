<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Factura Rapida</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="{{ asset('frontend/css/one-page-parallax/app.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('frontend/css/one-page-parallax/theme/red.min.css') }}" rel="stylesheet" />
        <!-- ================== END BASE CSS STYLE ================== -->
        <link href="{{ asset('admin/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />

        <link rel="shortcut icon" href="{{ asset('frontend/img/iconFR.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('frontend/img/iconFR.png') }}" type="image/x-icon">
        <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
    </head>
    <body data-spy="scroll" data-target="#header" data-offset="51">
        <!-- begin #page-container -->
        <div id="page-container" class="fade">
            <!-- begin #header -->
            <div id="header" class="header navbar navbar-transparent navbar-fixed-top navbar-expand-lg">
                <!-- begin container -->
                <div class="container">
                    <!-- begin navbar-brand -->
                    <a href="" class="navbar-brand">
                        <span class=""><img src="{{ asset('frontend/img/logoFR-2.png') }}" width="150" alt="Factura Rapida"></span>
                        <span class="brand-text">
                            <span class="text-primary"></span>
                        </span>
                    </a>
                    <!-- end navbar-brand -->
                    <!-- begin navbar-toggle -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- end navbar-header -->
                    <!-- begin navbar-collapse -->
                    <div class="collapse navbar-collapse" id="header-navbar">
                        <ul class="nav navbar-nav navbar-right">

                            <li class="nav-item"><a class="nav-link" href="#home" data-click="scroll-to-target">INICIO</a></li>
                            <li class="nav-item"><a class="nav-link" href="#about" data-click="scroll-to-target">NOSOTROS</a></li>
                            <li class="nav-item"><a class="nav-link" href="#service" data-click="scroll-to-target">SERVICIOS</a></li>
                            <li class="nav-item"><a class="nav-link" href="#pricing" data-click="scroll-to-target">PRECIOS</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contact" data-click="scroll-to-target">CONTACTO</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}" >REGISTRARSE</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}" >INGRESAR</a></li>

                        </ul>
                    </div>
                    <!-- end navbar-collapse -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #header -->

            <!-- begin #home -->
            <div id="home" class="content has-bg home">
                <!-- begin content-bg -->
                <div class="content-bg" style="background-image: url({{ asset('frontend/img/fondoFR.png') }})" 
                     data-paroller="true" 
                     data-paroller-factor="0.5"
                     data-paroller-factor-xs="0.25">
                </div>
                <!-- end content-bg -->
                <!-- begin container -->
                <div class="container home-content">
                    <h1>Factura Rápida</h1>
                    <h3>Mejor opción para la emisón de documentos electronicos.</h3>
                    <p>
                        Herramienta intuitiva para todo trabajador independiente, sus datos y documentos se mantendran seguros en nuestra plataforma, podras emitir documentos electronicos en cualquier momento y lugar, sin duda somos tu mejor opcion.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-theme btn-primary">INICIAR SESION</a> <a href="#pricing" data-click="scroll-to-target" class="btn btn-theme btn-outline-white">COMPRAR</a><br />
                    <br />
                </div>
                <!-- end container -->
            </div>
            <!-- end #home -->

            <!-- begin #about -->
            <div id="about" class="content" data-scrollview="true">
                <!-- begin container -->
                <div class="container" data-animation="true" data-animation-type="fadeInDown">
                    <h2 class="content-title">NOSOTROS</h2>
                    <p class="content-desc">
                        Ofrecemos soluciones simples, eficientes y seguras para el envío y recepción de documentos eléctronicos al Ministerio de Hacienda .
                    </p>
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <!-- begin about -->
                            <div class="about">
                                <h3 class="mb-3">MISIÓN</h3>
                                <p>
                                    Brindar un servicio de calidad para todas las personas, ayudando con su economia y proveyendo una herramienta simple que toda persona sea capaz de utilizar. 
                                </p>                                
                            </div>
                            <!-- end about -->
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">

                            <!-- begin about-author -->
                            <div class="about-author">
                                <div class="quote">
                                    <i class="fa fa-quote-left"></i>
                                    <h3>Pon en manos del Señor todas tus obras, y tus proyectos se cumplirán.</span></h3>
                                    <i class="fa fa-quote-right"></i>
                                </div>
                                <div class="author">
                                    <div class="image">
                                        <img src="{{ asset('frontend/img/b1.jpeg') }}" alt="" />
                                    </div>
                                    <div class="info">
                                        Proverbios
                                        <small>16:13</small>
                                    </div>
                                </div>
                            </div>
                            <!-- end about-author -->
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <!-- begin about -->
                            <div class="about">
                                <h3 class="mb-3">VISIÓN</h3>
                                <p>
                                    Llegar a ser el el sistema por excelencia en emisión y control de documentos electronicos. 
                                </p>
                            </div>
                            <!-- end about -->
                        </div>
                        <!-- end col-4 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #about -->

            <!-- begin #milestone -->
            <div id="milestone" class="content bg-black-darker has-bg" data-scrollview="true">
                <!-- begin content-bg -->
                <div class="content-bg" style="background-image: url({{ asset(' frontend/img/fondo4.jpg') }})"
                     ></div>
                <!-- end content-bg -->
                <!-- begin container -->
                <div class="container">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-3 -->
                        <div class="col-md-6 milestone-col">
                            <div class="milestone">
                                <div class="number" data-animation="true" data-animation-type="number" data-final-number="1292">1,292</div>
                                <div class="title">Visitas</div>
                            </div>
                        </div>
                        <!-- end col-3 -->
                        <!-- begin col-3 -->
                        <div class="col-md-6 milestone-col">
                            <div class="milestone">
                                <div class="number" data-animation="true" data-animation-type="number" data-final-number="415">415</div>
                                <div class="title">Miembros Registrados</div>
                            </div>
                        </div>
                        <!-- end col-3 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #milestone -->



            <!-- beign #service -->
            <div id="service" class="content" data-scrollview="true">
                <!-- begin container -->
                <div class="container">
                    <h2 class="content-title">NUESTROS SERVICIOS</h2>
                    <p class="content-desc">
                        FACTURA RÁPIDA pone a su disposición los servicios de emisión y recepción de documentos electrónicos según los estandares validos
                        por la DGT (Dirección General de tributación).
                    </p>
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-file-alt"></i></div>
                                <div class="info">
                                    <h4 class="title">Documentos Electrónicos</h4>
                                    <p class="desc">Emisión y envío de Facturas Electrónicas, Tiquetes Electrónicos, Notas de Débito y Notas de Crédito.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cart-arrow-down"></i></div>
                                <div class="info">
                                    <h4 class="title">Recepción</h4>
                                    <p class="desc">Recepción de gastos de forma automatica para su debido proceso de aceptación o rechazo.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-file"></i></div>
                                <div class="info">
                                    <h4 class="title">Factura de Compra</h4>
                                    <p class="desc">Realice sus facturas de compra desde nuestro sistema de una forma rápida y sencilla.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                    </div>
                    <!-- end row -->
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-database"></i></div>
                                <div class="info">
                                    <h4 class="title">Resguardo de Información</h4>
                                    <p class="desc">La información de sus clientes, proveedores, productos y asi como sus documentos electrónicos siempre estaran disponibles para su uso.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-book"></i></div>
                                <div class="info">
                                    <h4 class="title">Informes</h4>
                                    <p class="desc">Obtenga informes de sus ingresos y gastos que le ayudaran en la presentación de los impuestos de IVA y renta.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4 col-sm-12">
                            <div class="service">
                                <div class="icon" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-handshake"></i></div>
                                <div class="info">
                                    <h4 class="title">Trato Adecuado</h4>
                                    <p class="desc">Le ofrecemos precios competitivos y adecuados a su necesidad de emprendimiento.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end col-4 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #about -->

            <!-- begin #quote -->
            <div id="quote" class="content bg-black-darker has-bg" data-scrollview="true">
                <!-- begin content-bg -->
                <div class="content-bg" style="background-image: url({{ asset('frontend/img/fondo6.jpg') }})"
                     data-paroller-factor="0.5"
                     data-paroller-factor-md="0.01"
                     data-paroller-factor-xs="0.01">
                </div>
                <!-- end content-bg -->
                <!-- begin container -->
                <div class="container" data-animation="true" data-animation-type="fadeInLeft">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-12 -->
                        <div class="col-md-12 quote">
                            <i class="fa fa-quote-left"></i> Emita sus documentos de la forma más sencilla, <span class="text-primary">Factura Rápida</span> <br />
                            genera sus documentos con unos cuantos clicks. 
                            <i class="fa fa-quote-right"></i>
                        </div>
                        <!-- end col-12 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #quote -->

            <!-- begin #pricing -->
            <div id="pricing" class="content" data-scrollview="true">
                <!-- begin container -->
                <div class="container">
                    <h2 class="content-title">Planes postpago</h2>
                    <p class="content-desc">
                        Los mejores precios del mercado con generación de documentos ilimitados.
                    </p>
                    <!-- begin pricing-table -->
                    <ul class="pricing-table pricing-col-4">
                        <li data-animation="true" data-animation-type="fadeInUp">


                        </li>
                        <li data-animation="true" data-animation-type="fadeInUp">
                            <div class="pricing-container">
                                <h3>Mensual</h3>
                                <div class="price">
                                    <div class="price-figure">
                                        <span class="price-number">₡4 000</span>
                                        <span class="price-tenure">IVA incluido</span>
                                    </div>
                                </div>
                                <ul class="features">
                                    <li>Documentos ilimitados</li>
                                    <li>1 usuario</li>
                                    <li>Respaldo de información por 5 años</li>
                                    <li>Acceso desde cualquier dispositivo</li>
                                    <li>Soporte técnico</li>
                                </ul>
                                <div class="footer">
                                    <form method="post" action="{{ url('invoiceData')}}">
                                        @csrf
                                        <input hidden="true" type="number" name="plan" value="1" />
                                        <input hidden="true" type="number" name="type" value="1" />
                                        <div class="btn-group btn-group-justified">
                                            <button type="submit" title="Comprar" class="btn btn-primary btn-theme btn-block" ><i>Comprar</i></button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </li>
                        <li  data-animation="true" data-animation-type="fadeInUp">
                            <div class="pricing-container">
                                <h3>Anual</h3>
                                <div class="price">
                                    <div class="price-figure">
                                        <span class="price-number">₡44 000</span>
                                        <span class="price-tenure">IVA incluido</span>
                                    </div>
                                </div>
                                <ul class="features">
                                    <li>Documentos ilimitados</li>
                                    <li>1 usuario</li>
                                    <li>Respaldo de información por 5 años</li>
                                    <li>Acceso desde cualquier dispositivo</li>
                                    <li>Soporte técnico</li>                                    
                                </ul>
                                <div class="footer">
                                    <form method="post" action="{{ url('invoiceData') }}">
                                        @csrf
                                        <input hidden="true" type="number" name="plan" value="1" />
                                        <input hidden="true" type="number" name="type" value="2" />
                                        <div class="btn-group btn-group-justified">
                                            <button type="submit" title="Comprar" class="btn btn-primary btn-theme btn-block" ><i>Comprar</i></button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </li>
                        <li data-animation="true" data-animation-type="fadeInUp">

                        </li>
                    </ul>
                </div>
                <!-- end container -->
            </div>
            <!-- end #pricing -->
            <!-- begin #pricing -->
            <div id="pricing" class="content" data-scrollview="true">
                <!-- begin container -->
                <div class="container">
                    <h2 class="content-title">Planes prepago</h2>
                    <p class="content-desc">
                        Perfectos para pequeños empresarios con bajo volumen de facturación.
                    </p>
                    <!-- begin pricing-table -->
                    <ul class="pricing-table pricing-col-3">
                        
                        <li data-animation="true" data-animation-type="fadeInUp">
                            <div class="pricing-container">
                                <h3>Básico</h3>
                                <div class="price">
                                    <div class="price-figure">
                                        <span class="price-number">₡8 000</span>
                                        <span class="price-tenure">IVA incluido</span>
                                    </div>
                                </div>
                                <ul class="features">
                                    <li>20 Documentos</li>
                                    <li>1 usuario</li>
                                    <li>Respaldo de información por 5 años</li>
                                    <li>Acceso desde cualquier dispositivo</li>
                                    <li>Soporte técnico</li>
                                </ul>
                                <div class="footer">
                                    <form method="post" action="{{ url('invoiceData')}}">
                                        @csrf
                                        <input hidden="true" type="number" name="plan" value="2" />
                                        <input hidden="true" type="number" name="type" value="1" />
                                        <div class="btn-group btn-group-justified">
                                            <button type="submit" title="Comprar" class="btn btn-primary btn-theme btn-block" ><i>Comprar</i></button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </li>
                        <li data-animation="true" data-animation-type="fadeInUp">
                            <div class="pricing-container">
                                <h3>Medio</h3>
                                <div class="price">
                                    <div class="price-figure">
                                        <span class="price-number">₡16 000</span>
                                        <span class="price-tenure">IVA incluido</span>
                                    </div>
                                </div>
                                <ul class="features">
                                    <li>100 Documentos</li>
                                    <li>1 usuario</li>
                                    <li>Respaldo de información por 5 años</li>
                                    <li>Acceso desde cualquier dispositivo</li>
                                    <li>Soporte técnico</li>
                                </ul>
                                <div class="footer">
                                    <form method="post" action="{{ url('invoiceData')}}">
                                        @csrf
                                        <input hidden="true" type="number" name="plan" value="2" />
                                        <input hidden="true" type="number" name="type" value="2" />
                                        <div class="btn-group btn-group-justified">
                                            <button type="submit" title="Comprar" class="btn btn-primary btn-theme btn-block" ><i>Comprar</i></button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </li>
                        <li  data-animation="true" data-animation-type="fadeInUp">
                            <div class="pricing-container">
                                <h3>Avanzado</h3>
                                <div class="price">
                                    <div class="price-figure">
                                        <span class="price-number">₡40 000</span>
                                        <span class="price-tenure">IVA incluido</span>
                                    </div>
                                </div>
                                <ul class="features">
                                    <li>1000 Documentos</li>
                                    <li>1 usuario</li>
                                    <li>Respaldo de información por 5 años</li>
                                    <li>Acceso desde cualquier dispositivo</li>
                                    <li>Soporte técnico</li>                                    
                                </ul>
                                <div class="footer">
                                    <form method="post" action="{{ url('invoiceData') }}">
                                        @csrf
                                        <input hidden="true" type="number" name="plan" value="2" />
                                        <input hidden="true" type="number" name="type" value="3" />
                                        <div class="btn-group btn-group-justified">
                                            <button type="submit" title="Comprar" class="btn btn-primary btn-theme btn-block" ><i>Comprar</i></button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                </div>
                <!-- end container -->
            </div>
            <!-- end #pricing -->
            <!-- begin #quote -->
            <div id="quote" class="content bg-black-darker has-bg" data-scrollview="true">
                <!-- begin content-bg -->
                <div class="content-bg" style="background-image: url({{ asset('frontend/img/fondo1.png') }})"
                     data-paroller-factor="0.5"
                     data-paroller-factor-md="0.01"
                     data-paroller-factor-xs="0.01">
                </div>
                <!-- end content-bg -->
                <!-- begin container -->
                <div class="container" data-animation="true" data-animation-type="fadeInLeft">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-12 -->
                        <div class="col-md-12 quote">
                            <i class="fa fa-quote-left"></i> Disfruta el acceso desde cualquier dispositivo, <span class="text-primary">Factura Rápida</span> <br />
                            se adapta a todo tipo de pantalla   
                            <i class="fa fa-quote-right"></i>
                        </div>
                        <!-- end col-12 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #quote -->
            <!-- begin #contact -->
            <div id="contact" class="content bg-silver-lighter" data-scrollview="true">
                <!-- begin container -->
                <div class="container">
                    <h2 class="content-title">Contacto</h2>

                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-6 -->
                        <div class="col-lg-6" data-animation="true" data-animation-type="fadeInLeft">
                            <h3>Si tiene alguna duda, comentario o consulta, póngase en contacto con nosotros.</h3>

                            <p>
                                <strong>Cartago, Taras, San Nicolás</strong><br />
                                continuo a Super Baterias, Plaza Comercial Bon Genie<br />
                                Oficina #5<br />
                            </p>
                            <p>
                                <span class="phone">+506 8399 6444</span><br />
                                <a href="info@facturarapida.net" class="text-primary">info@facturarapida.net</a>
                            </p>
                        </div>
                        <!-- end col-6 -->
                        <!-- begin col-6 -->
                        <div class="col-lg-6 form-col" data-animation="true" data-animation-type="fadeInRight">
                            <form class="" method="post" action="{{ route('sendForm') }}">
                                @csrf
                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 text-md-right">Nombre <span class="text-primary">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="name" name="name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 text-md-right">Correo <span class="text-primary">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="email" name="email" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 text-md-right">Mensaje <span class="text-primary">*</span></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="message" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row m-b-15 text-right">
                                    <div class="g-recaptcha text-right" width="100%" data-sitekey="6Le-b4AaAAAAACr_YGpLsM8he-WizD1WEpEPmqrB"></div>
                                </div>
                                <div class="form-group row m-b-15 ">
                                    <label class="col-form-label col-md-3"></label>
                                    <div class="col-md-9 text-left">
                                        <button type="submit" class="btn btn-theme btn-primary btn-block">Enviar Mensaje</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end col-6 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end #contact -->
          <!--Start of Tawk.to Script-->

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5bdf7dbf4cfbc9247c1eae8c/1eh2jhcjb';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

        <!-- ================== BEGIN BASE JS ================== -->
        <script src="{{ asset('frontend/js/one-page-parallax/app.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
        <script src="{{ asset('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
        <script src="{{ asset('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
        <!-- ================== END BASE JS ================== -->
        @if(Session::has('success'))
        <script type="text/javascript">
function success() {
    $.gritter.add({
        title: 'Envío realizado con exito!',
        text: '{{ Session::get('success') }}',
        sticky: false,
        time: ''
    });
    return false;
}
success();
        </script>                               
        @endif   
        @if ($errors->any())
        <script type="text/javascript">
            function fail() {
                $.gritter.add({
                    title: 'Error en el envío del formularios!',
                    text: '{{ $errors }}',
                    sticky: false,
                    time: ''
                });
                return false;
            }
            fail();
        </script>        
        @endif
    </body>
</html>
