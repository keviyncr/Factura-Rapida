@extends('layouts.default')
@section('title', 'Exoneraciones')

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
        <h4 class="panel-title">Listado de exoneraciones</h4>
        <div class="panel-heading-btn">
            <a href="#modal-addExoneration"  data-toggle="modal" title="Nueva Exoneracion" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-exonerations" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="7%"data-orderable="false">Acciones</th>
                    <th width="25%" class="text-nowrap">Descricion</th>
                    <th width="10%" class="text-nowrap">Tipo de documento</th>
                    <th width="7%" class="text-nowrap">N. de documento</th>
                    <th width="25%" class="text-nowrap">Institucion</th>
                    <th width="10%" class=""> % </th>
                    <th width="10%" class="text-nowrap">Fecha de emision</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($exonerations as $exoneration)
                <tr class="gradeU">
                    <td width="1%" >{{ $exoneration->id }}</td>
                    <td>
                        <form method="post" action="{{ url ('exonerations/'.$exoneration->id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group btn-group-justified">
                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModalExoneration({{ $exoneration -> id }});"><i class="fa fa-edit"></i></button>
                                <button type="submit" title="Borrar" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                            </div>
                        </form> 
                    </td>
                    <td>{{ $exoneration->description }}</td>
                    <td>{{ $exoneration->type_doc }}</td>
                    <td>{{ $exoneration->document_number }}</td>
                    <td>{{ $exoneration->institutional_name }}</td>                    
                    <td>{{ $exoneration->exemption_percentage }}</td>
                    <td>{{ $exoneration->date }}</td>
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
<div class="modal fade" id="modal-addExoneration">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nueva Exoneracion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/exonerations') }}" method="POST" name="add_exoneration" id="add_exoneration" class="form-horizontal form-bordered">
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
                        <label class="col-lg-3 col-form-label">Tipo de documento: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="id_type_document_exoneration" id="id_type_document_exoneration" id="select-required">
                                @foreach($type_document_exonerations as $type_document_exoneration)                                       
                                <option style="color: black;" value="{{ $type_document_exoneration->id }}">{{ $type_document_exoneration->document }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Numero de documento y porcentaje exonerado: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <input  data-toggle="text" data-placement="after" class="form-control" type="text"  name="document_number" id="document_number" placeholder="Numero documento" data-parsley-required="true" />
                                </div>
                                <div class="col-xs-6 mb-2 mb-sm-0">
                                    <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="exemption_percentage" id="exemption_percentage" placeholder="% exoneracion" max="100" data-parsley-required="true" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre de la institucion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="institutional_name" id="institutional_name" data-parsley-required="true" class="form-control" placeholder="Nombre de la institucion" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">fecha de emision del documento de exoneracion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date" id="datepicker-default" data-date-format="yyyy-mm-dd"  >
                                <input type="text" id="date" name="date" class="form-control" placeholder="Fecha emision" ta-parsley-required="true" value="<?php echo date('Y-m-d'); ?>" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        </div>                       
                    </div>
                    <!-- end form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_exoneration" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addClient -->
<!-- #modal-updateClient -->
<div class="modal fade" id="modal-updateExoneration">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Exoneracion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/exonerations') }}" method="POST" name="update_exoneration" id="update_exoneration" class="form-horizontal form-bordered">
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
                        <label class="col-lg-3 col-form-label">Tipo de documento: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="id_type_document_exoneration" id="id_type_document_exonerationU" id="select-required">
                                @foreach($type_document_exonerations as $type_document_exoneration)                                       
                                <option style="color: black;" value="{{ $type_document_exoneration->id }}">{{ $type_document_exoneration->document }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Numero de documento y porcentaje exonerado: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <input  data-toggle="text" data-placement="after" class="form-control" type="text"  name="document_number" id="document_numberU" placeholder="Numero documento" data-parsley-required="true" />
                                </div>
                                <div class="col-xs-6 mb-2 mb-sm-0">
                                    <input  data-toggle="number" data-placement="after" class="form-control" type="number"  name="exemption_percentage" id="exemption_percentageU" placeholder="% exoneracion" max="100" data-parsley-required="true" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre de la institucion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="institutional_name" id="institutional_nameU" data-parsley-required="true" class="form-control" placeholder="Nombre de la institucion" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">fecha de emision del documento de exoneracion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date" id="datepicker-disabled-past" data-date-format="yyyy-mm-dd"  data-date-start-date="Date.default">
                                <input type="text" id="dateU" name="date" class="form-control" placeholder="Fecha emision" ta-parsley-required="true" value="<?php echo date('Y-m-d'); ?>" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        </div>                       
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_exoneration" class="btn btn-success">Actualizar</button>

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
<script src="{{ ('admin/js/exoneration.js') }}"></script>
<script src="{{ ('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>

@endpush

