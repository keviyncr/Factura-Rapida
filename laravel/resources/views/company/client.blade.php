@extends('layouts.default')
@section('title', 'Configuracion')

@push('css')
<link href="{{ asset('admin/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-autofill-bs4/css/autofill.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-colreorder-bs4/css/colreorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-keytable-bs4/css/keytable.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Listado de clientes</h4>
        <div class="panel-heading-btn">
            <a href="#modal-addClient"  data-toggle="modal" title="Nuevo cliente" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-client" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="9%"data-orderable="false">Acciones</th>
                    <th width="10%" class="text-nowrap">Identificacion</th>
                    <th width="15%" class="text-nowrap">Tipo identificacion</th>
                    <th width="20%" class="text-nowrap">Nombre</th>
                    <th width="10%" class="text-nowrap">Telefono</th>
                    <th width="20%" class="text-nowrap">Correo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr class="gradeU">
                    <td width="1%" >{{ $client->id }}</td>
                    <td>
                        <form method="post" action="{{ url ('clients/'.$client->id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group btn-group-justified">
                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModalClient({{ $client->id }});"><i class="fa fa-edit"></i></button>
                                <button type="submit" title="Borrar" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                            </div>
                        </form> 
                    </td>
                    <td>{{ $client->id_card }}</td>
                    <td>{{ $client->type_idCard }}</td>
                    <td>{{ $client->name_client }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->emails }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
@endsection
@section('content-modal')
<!-- #modal-addClient -->
<div class="modal fade" id="modal-addClient">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nuevo cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/clients') }}" method="POST" name="add_client" id="add_client" class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Cliente: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name_client" data-parsley-required="true" class="form-control" placeholder="Nombre Cliente" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Identifiacion y tipo de identificacion: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6 mb-2 mb-sm-0">
                                    <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="id_card" id="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12"  data-parsley-required="true" />
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="type_id_card" id="type_id_card" id="select-required">
                                        @foreach($type_id_cards as $type_id_card)                                       
                                        <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <input type="text" name="other_signs" data-parsley-required="true" class="form-control" placeholder="Otras señas"/>
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
                        <label class="col-lg-3 col-form-label">Codigo de pais y Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" >
                                        <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                        @foreach($country_codes as $country_code)
                                        @if($country_code->id == 52)
                                        <option selected="true" style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endif
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
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Condicion de venta y Plazo: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_condition" id="select-required">
                                        @foreach($sale_conditions as $sale_condition)                                       
                                        <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group col-xs-6 mb-2 mb-sm-0">
                                    <div class="input-group-prepend"><span class="input-group-text">dias</span></div> 
                                    <input  class="form-control" type="number"  name="time" id="time" value="1" min="1" max="99" data-parsley-required="true"/>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Moneda y metodo de pago: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_currency" id="id_currency" id="select-required">
                                        @foreach($currencies as $currency) 
                                        @if($currency->id == 55)
                                        <option selected="true" style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $currency->id }}">{{ $currency->currency." - ".$currency->code }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_method" id="select-required">
                                        @foreach($payment_methods as $payment_method)                                       
                                        <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_client" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addClient -->
<!-- #modal-updateClient -->
<div class="modal fade" id="modal-updateClient">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar datos de cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/clients') }}" method="POST" name="update_client" id="update_client" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Cliente: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name_client" id="name_clientU" data-parsley-required="true" class="form-control" placeholder="Nombre Cliente" />
                            <input hidden="true" type="text" name="id" id="idU" data-parsley-required="true" class="form-control" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Identifiacion y tipo de identificacion: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6 mb-2 mb-sm-0">
                                    <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="id_card" id="id_cardU" placeholder="Numero Identificación" minlength="9" maxlength="12" data-parsley-group="step-1" data-parsley-required="true" />
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="type_id_card" id="type_id_cardU" id="select-required">
                                        @foreach($type_id_cards as $type_id_card)                                       
                                        <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_province" id="id_provinceU" data-parsley-group="step-2">                                                               
                                        <option style="color: black;" value="0" selected="true" disabled="true">Provincia</option>
                                        @foreach($provinces as $province)
                                        <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_canton" id="id_cantonU" data-parsley-group="step-2">
                                        <option style="color: black;" value="0" selected="true" disabled="true">Cantón</option>

                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_district" id="id_districtU" data-parsley-group="step-2">
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
                                    <input type="text" name="other_signs" id="other_signsU" data-parsley-required="true" class="form-control" placeholder="Otras señas"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="emails" id="emailsU" class="form-control" data-parsley-group="step-2" data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico para informacion (va en la factura)"/>
                        </div>
                    </div>
                    <!-- end form-group -->

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Codigo de pais y Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" id="id_country_codeU" >
                                        <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                        @foreach($country_codes as $country_code)
                                        @if($country_code->id == 52)
                                        <option selected="true" style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number"  name="phone" id="phoneU" placeholder="N. Telefonico" minlength="8" maxlength="8" data-parsley-group="step-2" data-parsley-required="true"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Condicion de venta y Plazo: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_conditionU" id="select-required">
                                        @foreach($sale_conditions as $sale_condition)                                       
                                        <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group col-xs-6 mb-2 mb-sm-0">
                                    <div class="input-group-prepend"><span class="input-group-text">dias</span></div> 
                                    <input  class="form-control" type="number"  name="time" id="timeU" value="1" min="1" max="99" data-parsley-required="true"/>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Moneda y metodo de pago: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_currency" id="id_currencyU" id="select-required">
                                        @foreach($currencies as $currency) 
                                        @if($currency->id == 55)
                                        <option selected="true" style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $currency->id }}">{{ $currency->currency." - ".$currency->code }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_methodU" id="select-required">
                                        @foreach($payment_methods as $payment_method)                                       
                                        <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_client" class="btn btn-success">Actualizar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-updateClient -->
@endsection

@push('scripts')
<script src="{{ asset('admin/plugins/parsleyjs/dist/parsley.js') }}"></script>
<script src="{{ asset('admin/plugins/smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('admin/js/app/wizards-validation.js') }}"></script>
<script src="{{ asset('admin/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('admin/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/js/app/form-plugins.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-autofill/js/dataTables.autoFill.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-colreorder/js/dataTables.colReorder.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-colreorder-bs4/js/colReorder.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-keytable-bs4/js/keyTable.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-rowreorder/js/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/js/rowReorder.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-select/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons/js/buttons.html5.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('admin/js/demo/table-manage-select.demo.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script src="{{ asset('admin/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/plugins/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('admin/js/clients.js') }}"></script>
<script src="{{ asset('admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ asset('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
<script src="{{ asset('admin/js/address.js') }}"></script>
@endpush

