@extends('layouts.default')
@section('title', 'Importacion')

@push('css')
<link href="{{ asset('admin/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-autofill-bs4/css/autofill.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-colreorder-bs4/css/colreorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-keytable-bs4/css/keytable.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" />

<link href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />

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
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Importacion de facturas de Glovers</h4>
        <div class="panel-heading-btn">
            @if(isset($lines))
            <a href="#modal-addfile"  data-toggle="modal" title="Importar lines" class="btn btn-danger"><i class="fa fa-upload"> Importar</i></a>
            @else
            <a href="#modal-addfile"  data-toggle="modal" title="Cargar documento" class="btn btn-danger"><i class="fa fa-upload"> Cargar archivo</i></a>
            @endif

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-products" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="7%"data-orderable="false">Emisor</th>
                    <th width="10%" class="text-nowrap">Receptor</th>
                    <th width="4%" class="text-nowrap">Descripcion</th>
                    <th width="7%" class="text-nowrap">Unid Medida</th>
                    <th width="7%" class="text-nowrap">Cantidad</th>
                    <th width="7%" class="text-nowrap">Precio</th>
                    <th width="7%" class="text-nowrap">Impuesto</th>  
                    <th width="7%" class="text-nowrap">Descuento</th>                     
                    <th width="7%" class="text-nowrap">Total</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($lines))
                @foreach($lines as $index=> $line)
                 @if($index>0)
                    <tr class="gradeU">
                        <td width="1%" >{{ $index }}</td>
                        <td>{{ $line[0] }}</td>
                        <td>{{ $line[1] }}</td>
                        <td>{{ $line[2] }}</td>                    
                        <td>{{ $line[3] }}</td>
                        <td>{{ $line[4] }}</td>
                        <td>{{ $line[5] }}</td>
                        <td>{{ $line[6] }}</td>
                        <td>{{ $line[7] }}</td>
                        <td>{{ $line[8] }}</td>
                        
                    </tr>
                    @endif
                @endforeach
                @endif
            </tbody>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
@endsection
@section('content-modal')
<!-- #modal-addProduct -->
<div class="modal fade" id="modal-addfile">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cargar Archivo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/importChargeFile') }}" method="Post" name="add_file" id="add_file" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Archivo (*xlsx): <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input data-toggle="file" data-placement="after" class="form-control" type="file"  id="fileInvoices" name="fileInvoices" placeholder="Archivo para cargar" data-parsley-required="true" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_file" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addProduct -->

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
<script src="{{ asset('admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ asset('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
<script src="{{ asset('admin/js/product.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

@endpush

