@extends('layouts.default')
@section('title', 'Inicio')

@push('css')
<link href="{{ asset('admin/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-autofill-bs4/css/autofill.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-colreorder-bs4/css/colreorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-keytable-bs4/css/keytable.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" />







<link href="{{ asset('admin/plugins/jvectormap-next/jquery-jvectormap.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/nvd3/build/nv.d3.css" rel="stylesheet') }}" />
<link href="{{ asset('admin/plugins/smartwizard/dist/css/smart_wizard.css') }}" rel="stylesheet" />

<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/@danielfarrell/bootstrap-combobox/css/bootstrap-combobox.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/tag-it/css/jquery.tagit.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css') }}" rel="stylesheet" />
@endpush

@section('content')

<!-- begin breadcrumb -->
<div class="row">   
    <!-- begin col-6 -->
    <div class="col-xl-12">
        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-sm-6">
                <!-- begin card -->
                <div class="card border-0 text-truncate mb-3">
                    <!-- begin card-body -->
                    <div class="card-body">
                        <!-- begin title -->
                        <div class="mb-3 text-grey">
                            <b class="mb-3">TOTAL DE INGRESOS DE LOS ULTIMOS 12 MESES {{ session('desde') }}</b> 
                        </div>
                        <!-- end title -->
                        <!-- begin conversion-rate -->
                        <div class="d-flex align-items-center mb-1">
                            <h2 class="text-white mb-0">CRC <span data-animation="number" data-value="{{ round(session('salesY'),2) }}">0.00</span></h2>
                            <div class="ml-auto">
                                <div id="conversion-rate-sparkline"></div>
                            </div>
                        </div>
                        <!-- end conversion-rate -->
                        <!-- begin percentage -->
                        <div class="mb-4 text-grey">
                            CRC  <span data-animation="number" class="text-blue"data-value="{{ round(session('salesM'),2) }}">0.00</span> PROMEDIO DE INGRESO POR MES
                        </div>
                        <!-- end percentage -->
                        <!-- begin info-row -->
                        <div class="d-flex mb-2">
                            <div class="d-flex align-items-center ">
                                <i class="fa fa-circle text-red f-s-8 mr-2"></i>
                                MES {{ date('m') }}
                            </div>
                            <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('salesM1'),2) }}">0</span> CRC</div>
                            </div>
                            
                            
                        </div>
                        <!-- end info-row -->
                        <!-- begin info-row -->
                        <div class="d-flex mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-circle text-warning f-s-8 mr-2"></i>
                                MES {{ date('m')-1 }}
                            </div>
                            <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('salesM2'),2) }}">0</span> CRC</div>
                            </div>
                        </div>
                        <!-- end info-row -->
                        <!-- begin info-row -->
                        <div class="d-flex">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-circle text-lime f-s-8 mr-2"></i>
                                MES {{ date('m')-2 }}
                            </div>
                            <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('salesM3'),2) }}">0</span> CRC</div>
                            </div>
                        </div>
                        <!-- end info-row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col-6 -->
            <!-- begin col-6 -->
            <div class="col-sm-6">
                <!-- begin card -->
                <div class="card border-0 text-truncate mb-3">
                    <!-- begin card-body -->
                    <div class="card-body">
                        <!-- begin title -->
                        <div class="mb-3 text-grey">
                            <b class="mb-3">PROMEDIO DE GASTOS DE LOS ULTIMOS 12 MESES</b> 
                        </div>
                        <!-- end title -->
                        <!-- begin store-session -->
                        <div class="d-flex align-items-center mb-1">
                            <h2 class="text-white mb-0">CRC <span data-animation="number" data-value="{{ round((session('purchasesY')),2) }}">0.00</span></h2>
                            <div class="ml-auto">
                                <div id="store-session-sparkline"></div>
                            </div>
                        </div>
                        <!-- end store-session -->
                        <!-- begin percentage -->
                        <div class="mb-4 text-grey">
                            CRC  <span data-animation="number" class="text-blue"data-value="{{ round(session('purchasesM'),2) }}">0.00</span> PROMEDIO DE GASTOS POR MES
                        </div>
                        <!-- end percentage -->
                        <!-- begin info-row -->
                        <div class="d-flex mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-circle text-teal f-s-8 mr-2"></i>
                                 MES {{ date('m') }}
                            </div>
                            <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('purchasesM1'),2) }}">0</span> CRC</div>
                            </div>
                        </div>
                        <!-- end info-row -->
                        <!-- begin info-row -->
                        <div class="d-flex mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-circle text-blue f-s-8 mr-2"></i>
                                 MES {{ date('m')-1 }}
                            </div>
                           <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('purchasesM2'),2) }}">0</span> CRC</div>
                            </div>
                        </div>
                        <!-- end info-row -->
                        <!-- begin info-row -->
                        <div class="d-flex">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-circle text-aqua f-s-8 mr-2"></i>
                                 MES {{ date('m')-2 }}
                            </div>
                            <div class="d-flex align-items-center ml-auto">
                                <div class="text-grey f-s-11"> <span data-animation="number" data-value="{{ round(session('purchasesM3'),2) }}">0</span> CRC</div>
                            </div>
                        </div>
                        <!-- end info-row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end col-6 -->
</div>
<!-- end row -->
@endsection
@section('content-modal')

@if(count($companies) == 0)
<!-- modal para primer inicio -->
<div class="modal modal-message" id="config-Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Para continuar primero debe brindar los datos solicitados.</h4>
            </div>
            <div class="modal-body">

                <form action="{{ url('/home') }}" method="POST"  class="form-horizontal" enctype="multipart/form-data" name="form-wizard" class="form-control-with-bg">
                    @csrf
                    <!-- begin wizard -->
                    <div id="wizard">
                        <!-- begin wizard-step -->
                        <ul>
                            <li>
                                <a href="#step-1">
                                    <span class="number">1</span> 
                                    <span class="info">
                                        Datos de la empresa
                                        <small>Datos ingresados en ATV</small>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-2">
                                    <span class="number">2</span> 
                                    <span class="info">
                                        Datos de la sucursal
                                        <small>Datos ingresados en ATV</small>
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#step-3">
                                    <span class="number">3</span>
                                    <span class="info">
                                        Completar
                                        <small>Guardar datos</small>
                                    </span>
                                </a>
                            </li>

                        </ul>
                        <!-- end wizard-step -->
                        <!-- begin wizard-content -->
                        <div>
                            <!-- begin step-1 -->
                            <div id="step-1">
                                <!-- begin fieldset -->
                                <fieldset>
                                    <!-- begin row -->
                                    <div class="row">
                                        <!-- begin col-8 -->
                                        <div class="col-xl-8 offset-xl-2">
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Nombre compañia: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="text" name="name_company" data-parsley-group="step-1" data-parsley-required="true" class="form-control" placeholder="Nombre de la compañia"/>
                                                </div>
                                            </div>
                                            <!-- end form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Identifiacion: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="row row-space-6">
                                                        <div class="col-6">
                                                            <input data-toggle="number" data-placement="after" class="form-control" type="number"  name="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12" data-parsley-group="step-1" data-parsley-required="true"  />
                                                        </div>
                                                        <div class="col-6">
                                                            <select class="form-control" data-parsley-required="true" name="type_id_card" id="select-required">
                                                                <option style="color: black;" value="" disabled selected="true">Tipo identifiación</option>
                                                                @foreach($type_id_cards as $type_id_card)
                                                                <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Usuario MH: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="text" name="user_mh" data-parsley-group="step-1" data-parsley-required="true" class="form-control" placeholder="Usuario brindado en el ATV"/>
                                                </div>
                                            </div>
                                            <!-- end form-group -->

                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Contraseña MH: </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input data-toggle="password" data-placement="after" class="form-control" type="password"  name="pass_mh" data-parsley-group="step-1" data-parsley-required="true"  placeholder="Contraseña brindada en el ATV"/>
                                                </div>
                                            </div>
                                            <!-- end form-group -->
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Lave criptografica: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input data-toggle="file" data-placement="after" class="form-control" type="file"  id="criptKey" name="cryptographic_key" placeholder="Llave Criptografica" data-parsley-group="step-1" data-parsley-required="true" />
                                                </div>
                                            </div>                                            
                                            <!-- end form-group -->  
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">PIN: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input data-toggle="password" data-placement="after" class="form-control" type="password"  name="pin" placeholder="PIN de llave criptografica" minlength="4" maxlength="4" data-parsley-group="step-1" data-parsley-required="true"  />
                                                </div>
                                            </div>                                            
                                            <!-- end form-group --> 
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Actividad económica: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <select class="default-select2 form-control" name="economic_activities" >
                                                        <option style="color: black;" value="" disabled selected="true">Actividad Economica</option>
                                                        @foreach($economic_activities as $economic_activity)
                                                        <option style="color: black;" value="{{ $economic_activity->id }}">{{ $economic_activity->number." - ".$economic_activity->name_ea }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>    
                                        </div>
                                        <!-- end col-8 -->
                                    </div>
                                    <!-- end row -->
                                </fieldset>
                                <!-- end fieldset -->
                            </div>
                            <!-- end step-1 -->
                            <!-- begin step-2 -->
                            <div id="step-2">
                                <!-- begin fieldset -->
                                <fieldset>
                                    <!-- begin row -->
                                    <div class="row">
                                        <!-- begin col-8 -->
                                        <div class="col-xl-8 offset-xl-2">
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Nombre sucursal: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="text" name="name_branch_office" data-parsley-group="step-2" data-parsley-required="true" class="form-control" placeholder="Nombre de la sucursal"/>
                                                </div>
                                            </div>
                                            <!-- end form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="row row-space-6">
                                                        <div class="col-4">
                                                            <select class="form-control" data-parsley-required="true" name="id_province" id="id_province" data-parsley-group="step-2">                                                               
                                                                <option style="color: black;" value="0" selected="true" disabled="true">Provincia</option>
                                                                @foreach($provinces as $province)
                                                                <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <select class="form-control" data-parsley-required="true" name="id_canton" id="id_canton" data-parsley-group="step-2">
                                                                <option style="color: black;" value="0" selected="true" disabled="true">Cantón</option>

                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <select class="form-control" data-parsley-required="true" name="id_district" id="id_district" data-parsley-group="step-2">
                                                                <option style="color: black;" value="0" selected="true" disabled="true">Distrito</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end form-group -->
                                            <div class="form-group row m-b-10">
                                                <div class="col-lg-12 col-xl-12">
                                                    <div class="row row-space-12">                                                        
                                                        <div class="col-lg-12 col-xl-12">
                                                            <input type="text" name="other_signs" data-parsley-group="step-2" data-parsley-required="true" class="form-control" placeholder="Otras señas"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="email" name="emails" class="form-control" data-parsley-group="step-2" data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico para informacion (va en la factura)"/>
                                                </div>
                                            </div>
                                            <!-- end form-group -->

                                            <!-- begin form-group -->
                                            <div class="form-group row m-b-10">
                                                <label class="col-lg-3 col-form-label">Telefono: </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="row row-space-6">
                                                        <div class="col-6">
                                                            <select class="default-select2 form-control" name="id_country_code" id="id_country_code" >
                                                                <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                                                @foreach($country_codes as $country_code)
                                                                <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <input data-toggle="number" data-placement="after" class="form-control" type="number"  name="phone" placeholder="N. Telefonico" minlength="8" maxlength="8" data-parsley-group="step-2" data-parsley-required="true"  />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end form-group -->

                                        </div>
                                        <!-- end col-8 -->
                                    </div>
                                    <!-- end row -->
                                </fieldset>
                                <!-- end fieldset -->
                            </div>
                            <!-- end step-2 -->
                            <!-- begin step-3 -->
                            <div id="step-3">
                                <div class="jumbotron m-b-0 text-center">
                                    <h2 class="display-4">¡ATENCIÓN!</h2>
                                    <p class="lead mb-4">Si se realiza alguna modificación de los datos suminitrados en la plataforma ATV o en el correo electrónico de recepción, también debe de realizar la actualizacion de dichos datos en nuestra plataforma. <br />La equidad de estos datos en ambos sistemas es importante para su buen funcionamiento. </p>
                                    <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
                                </div>
                            </div>
                            <!-- end step-3 -->
                        </div>
                        <!-- end wizard-content -->
                    </div>
                    <!-- end wizard -->
                </form>
                <!-- end wizard-form -->
            </div>
        </div>
    </div>
</div>
@endif

@endsection


@push('scripts')
<script src="{{ asset('admin/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('admin/plugins/nvd3/build/nv.d3.js') }}"></script>
<script src="{{ asset('admin/plugins/jvectormap-next/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jvectormap-next/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ asset('admin/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/js/app/dashboard-v3.js') }}"></script>




<script src="{{ asset('admin/plugins/parsleyjs/dist/parsley.js') }}"></script>
<script src="{{ asset('admin/plugins/smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('admin/js/app/wizards-validation.js') }}"></script>
<script src="{{ asset('admin/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('admin/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/js/app/form-plugins.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('admin/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/@danielfarrell/bootstrap-combobox/js/bootstrap-combobox.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('admin/plugins/tag-it/js/tag-it.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('admin/js/address.js') }}"></script>
@if(count($companies) == 0)
<script>
    $(document).ready(function ()
    {
        $('#config-Modal').modal({backdrop: 'static', keyboard: false});
        $("#config-Modal").modal("show");
    });
</script>
@endif
@endpush