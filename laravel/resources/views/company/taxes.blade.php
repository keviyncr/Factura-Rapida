@extends('layouts.default')
@section('title', 'Impuestos')

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
        <h4 class="panel-title">Listado de Impuestos</h4>
        <div class="panel-heading-btn">
            <a href="#modal-addTax"  data-toggle="modal" title="Nueva Exoneracion" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-taxes" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="5%"data-orderable="false">Acciones</th>
                    <th width="10%" class="text-nowrap">Descripcion</th>
                    <th width="15%" class="text-nowrap">Tipo de impuesto</th>
                    <th width="5%" class="text-nowrap">Tarifa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taxes as $tax)
                <tr class="gradeU">
                    <td width="1%" >{{ $tax->id }}</td>
                    <td>
                        <form method="post" action="{{ url ('taxes/'.$tax->id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group btn-group-justified">
                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModalTax({{ $tax -> id }});"><i class="fa fa-edit"></i></button>
                                <button type="submit" title="Borrar" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                            </div>
                        </form> 
                    </td>
                    <td>{{ $tax->description }}</td>
                    <td>{{ $tax->taxes_code_description }}</td>
                    <td>{{ $tax->rate }}%</td>
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
<div class="modal fade" id="modal-addTax">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nuevo impuesto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/taxes') }}" method="POST" name="add_tax" id="add_tax" class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre o descripcion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="description" id="description" data-parsley-required="true" class="form-control" placeholder="Nombre o descripcion" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tipo de impuesto: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select  class="form-control" data-parsley-required="true" name="id_taxes_code" id="id_taxes_code" id="select-required" onchange="taxes_code_change()">
                                <option style="color: black;" value="">Ninguno</option>
                                @foreach($taxes_codes as $tax_code)                                       
                                <option style="color: black;" value="{{ $tax_code->id }}">{{ $tax_code->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10" hidden="true"  id="group_taxes_code">
                        <label class="col-lg-3 col-form-label">Tipo de Tarifa: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select  class="form-control"  name="id_rate_code" id="id_rate_code" id="select-required" onchange="id_rate_code_change()">
                                <option selected="true" style="color: black;" value="0">Ninguno</option>
                                @foreach($rate_codes as $rate_code)                                       
                                <option style="color: black;" value="{{ $rate_code->id }}">{{ $rate_code->description  }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                   <!-- begin form-group -->
                    <div class="form-group row" id="group_rate">
                        <label class="col-lg-3 col-form-label">Tarifa %: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input value="0" data-toggle="number" data-placement="after" class="form-control" type="number"  name="rate" id="rate" placeholder="Tarifa %" max="100" data-parsley-required="true" />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row" hidden="true" id="group_rateIVA">
                        <label class="col-lg-3 col-form-label">Factor IVA %: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="rateIVA" id="rateIVA" placeholder="Factor IVA" placeholder="0.0000" step="0.0001" max="99999" min="0" value="0" />
                        </div>
                    </div>
                    <!-- end form-group -->
                    
                    
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Exoneracion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="id_exoneration" id="id_exoneration" id="select-required">
                                <option style="color: black;" value="0">Ninguna</option>
                                @foreach($exonerations as $exoneration)                                       
                                <option style="color: black;" value="{{ $exoneration->id }}">{{ $exoneration->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_tax" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addClient -->
<!-- #modal-updateClient -->
<div class="modal fade" id="modal-updateTax">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Exoneracion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/taxes') }}" method="POST" name="update_tax" id="update_tax" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre o descripcion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="description" id="descriptionU" data-parsley-required="true" class="form-control" placeholder="Nombre o descripcion" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tipo de impuesto: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select  class="form-control" data-parsley-required="true" name="id_taxes_code" id="id_taxes_codeU" id="select-required" onchange="taxes_code_changeU()">
                                <option style="color: black;" value="">Ninguno</option>
                                @foreach($taxes_codes as $tax_code)                                       
                                <option style="color: black;" value="{{ $tax_code->id }}">{{ $tax_code->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10" hidden="true"  id="group_taxes_codeU">
                        <label class="col-lg-3 col-form-label">Tipo de Tarifa: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select  class="form-control"  name="id_rate_code" id="id_rate_codeU" onchange="id_rate_code_changeU()">
                                <option style="color: black;" value="0">Ninguno</option>
                                @foreach($rate_codes as $rate_code)                                       
                                <option style="color: black;" value="{{ $rate_code->id }}">{{ $rate_code->description  }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                   <!-- begin form-group -->
                    <div class="form-group row" id="group_rateU">
                        <label class="col-lg-3 col-form-label">Tarifa %: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input value="0" data-toggle="number" data-placement="after" class="form-control" type="number"  name="rate" id="rateU" placeholder="Tarifa %" max="100" data-parsley-required="true" />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row" hidden="true" id="group_rateIVAU">
                        <label class="col-lg-3 col-form-label">Factor IVA: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="rateIVA" id="rateIVAU"  placeholder="0.0000" step="0.0001"  max="99999" min="0" value="0" />
                        </div>
                    </div>
                    <!-- end form-group -->                    
                    
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Exoneracion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="id_exoneration" id="id_exonerationU" id="select-required">
                                <option style="color: black;" value="0">Ninguna</option>
                                @foreach($exonerations as $exoneration)                                       
                                <option style="color: black;" value="{{ $exoneration->id }}">{{ $exoneration->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_tax" class="btn btn-success">Actualizar</button>

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
<script src="{{ ('admin/js/demo/table-manage-select.demo.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script src="{{ ('admin/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ ('admin/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ ('admin/plugins/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ ('admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ ('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ ('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
<script src="{{ ('admin/js/tax.js') }}"></script>
<script src="{{ ('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>

@endpush

