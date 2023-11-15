@extends('layouts.app')
@section('title', 'Detalle de Vnetas')

@push('css')
<link href="{{ asset('admin/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-autofill-bs4/css/autofill.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-colreorder-bs4/css/colreorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-keytable-bs4/css/keytable.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" />




<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />

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
<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" />

@endpush

@section('content')
<!-- begin profile -->
<div class="profile" width="100%">
    <div class="profile-header">
        <!-- BEGIN profile-header-cover -->
        <div class="profile-header-conver">
            <h4 class="panel-title">Detalle de Ventas</h4>
        </div>
        <!-- END profile-header-cover -->
        <!-- BEGIN profile-header-content -->
        <div class="profile-header-content" style="background-color:black;">
            <form data-parsley-validate="true" action="{{ url('/dExpenses') }}" method="POST">
            <div class="panel-heading-btn">
            <!--agregar los rango-->
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
             
				<div class=" row row-space-12">
					<div class="col-lg-12 row row-space-12">
                        <label class="col-lg-1 col-form-label">Filtrar:</label>
						<div class="col-xs-2 mb-2 mb-sm-0">
						    <input type="text" class="form-control" id="datepicker-f1" name="f1" placeholder="Fecha Inicio" value="{{ $fecha1 = (isset($f1))?$f1:date('Y-m-d') }}"  />
						</div>
						<div class="col-xs-2  mb-2 mb-sm-0">
							<input type="text" class="form-control" id="datepicker-f2" name="f2" placeholder="Fecha Final" value="{{ $fecha2 = (isset($f2))?$f2:date('Y-m-d') }}" />
						</div>
						<div class="col-xs-3 mb-2 mb-sm-0">
    						<select class="default-select2 form-control" name="economic_activities" id="economic_activities" required="true" >
                                <option style="color: black;" value="0" selected="true">Actividad Economica</option>
                                @foreach($economic_activities as $economic_activity)
                                    <option style="color: black;" value="{{ $economic_activity->id }}">{{ $economic_activity->number." - ".$economic_activity->name_ea }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1  mb-2 ">
							<input type="submit" class="form-control btn btn-danger" name="btn1" value = "Cargar" />
						</div>
						<div class="col-xs-3  mb-2 ">
							<input type="submit" class="form-control btn btn-danger"  name="btn2" value = "Descargar informe completo" />
						</div>
					</div>
				</div>
            </div>
        </form>
        </div>
        <!-- END profile-header-content -->
    </div>
</div>
<!-- end profile -->
<br>
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ventas</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body" width="100%" style="font-size:10px">
        <table id="data-table-report" class="table-striped table-bordered table-td-valign-middle" width="100%">
            <thead>
                <tr>
                    <th width="1%" data-orderable="false">N°</th>
                    <th width="11%" data-orderable="false">Fecha</th>
                    <th width="11%" data-orderable="false">Numero Factura</th>
                    <th width="11%" data-orderable="false">Emisor</th>
                    <th width="11%" data-orderable="false">Item</th>
                    <th width="7%" data-orderable="false">Monto</th>
                    <th width="7%" data-orderable="false">Desc.</th>
                    <th width="7%" data-orderable="false">Impuesto</th>  
                    <th width="7%" data-orderable="false">Total</th>   
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Impuesto</th>
                    <th width="10%" data-orderable="false">Categoria</th>
                    <th width="10%" data-orderable="false">Act. Econom.</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($detailG))
                    @foreach($detailG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="11%" >{{ $lineG["fechaG"] }}</td>
                            <td width="11%" >{{ $lineG["claveG"] }}</td>
                            <td width="11%" >{{ $lineG["emisor"] }}</td>
                            <td width="11%" >{{ $lineG["itemG"] }}</td>
                            <td width="7%" >{{ round($lineG["montoG"],2) }}</td>
                            <td width="7%" >{{ round($lineG["discountG"],2) }}</td>
                            <td width="7%" >{{ round($lineG["taxG"],2) }}</td>
                            <td width="7%" >{{ round($lineG["totalG"],2) }}</td>
                            <td width="7%" >{{ $lineG["tmoneda"] }}</td>
                            <td>{{ (isset($lineG["typeTaxG"])?$lineG["typeTaxG"]:0)."%" }}</td>
                            <td width="10%" >{{ $lineG["category"] }}</td>
                            <td width="10%" >{{ round($lineG["aeG"],2) }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($detailG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ round($sumaMontoG,2) }}</td>
                      <td width="5%" >{{ round($sumaDescuentoG,2) }}</td>
                      <td width="5%" >{{ round($sumaImpuestoG,2) }}</td>
                      <td width="8%" >{{ round($sumaTotalG,2) }}</td>
                      <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>

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
<script src="{{ asset('admin/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
	


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="{{ asset('admin/plugins/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/@danielfarrell/bootstrap-combobox/js/bootstrap-combobox.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/tag-it/js/tag-it.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-show-password/dist/bootstrap-show-password.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js') }}"></script>
	<script src="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js') }}"></script>
	<script src="{{ asset('admin/plugins/clipboard/dist/clipboard.min.js') }}"></script>
	<script src="{{ asset('admin/js/demo/form-plugins.demo.js') }}"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

@endpush

