@extends('layouts.default')
@section('title', 'Comprobantes')

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
        <h4 class="panel-title">Listado de Comprobantes</h4>
        @if( Auth::user()->roll != "SuperGlovers" )
        <div class="panel-heading-btn">
           
            <div class="btn-group m-r-5 m-b-5">
               <form data-parsley-validate="true" action="{{ url('/vouchers') }}" method="GET">
            <div class="panel-heading-btn">
            <!--agregar los rango-->
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
             
				<div class=" row row-space-12">
					
				</div>
            </div>
        </form>
            </div>
            <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modal-importVoucher">Importar</a>
        </div>
        @endif
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-products" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="19%"data-orderable="false">Acciones</th>
                    <th width="10%" class="text-nowrap">Fecha</th>
                    <th width="10%" class="text-nowrap">Cedula</th>                    
                    <th width="24%" class="text-nowrap">Nombre Emisor</th>                    
                    <th width="1%" class="text-nowrap">Moneda</th>  
                    <th width="10%" class="text-nowrap">Total</th>
                    <th width="24%" class="text-nowrap">Clave</th>
                </tr>
            </thead>

            <tbody>
                @php  $cont = 1 @endphp
                @if(isset($vouchers))
                @foreach($vouchers as $voucher)

                <tr class="gradeU">
                    <td width="1%" >{{ $cont++ }}</td>
                    <td>                        
                        <div class="btn-group btn-group-justified">

                            <form method="post" action="{{ url ('vouchers/'.$voucher["clave"])}}">
                                @csrf
                                @method('DELETE')
                                <div class="btn-group btn-group-justified">
                                   
                                @if(file_exists( $voucher["ruta"].$voucher["clave"].'/'.$voucher["clave"].'.pdf'  ))
                                <a href="{{ $voucher['ruta'].$voucher["clave"].'/'.$voucher['clave'].'.pdf' }}" target="_blank" class="btn btn-default"><i title="Vista del Documento" class="fa fa-search"></i></a>
                                @else
                                <a href="javascript:onclick=viewVoucher('{{ $voucher['clave'] }}');" class="btn btn-default"><i title="Vista de Factura" class="fa fa-search"></i></a>
                                @endif
                                    <a href="javascript:onclick=proccess('onlySave','{{ $voucher["clave"]  }}');"  title="Solo guardar" class="btn btn-default btn-md"><i class=" fa fa-save"></i></a>
                                    <a href="javascript:onclick=proccess('accept','{{ $voucher["clave"]  }}');"  title="Aceptar" class="btn btn-default btn-md"><i class=" fa fa-check"></i></a>
                                    <a href="javascript:onclick=proccess('refuse','{{ $voucher["clave"]  }}');"  title="Rechazar" class="btn btn-default btn-md"><i class=" fa fa-times"></i></a> 
                                    <button type="submit" title="Borrar" class="btn btn-default btn-md" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                                </div>
                            </form> 
                        </div>
                    </td>
                    <td>{{ substr($voucher["fecha"], 0, 10) }}</td>
                    <td>{{ $idcar = (isset($voucher["emisor"]->Identificacion->Numero))?$voucher["emisor"]->Identificacion->Numero:"" }}</td>
                    <td>{{ $emisor=(isset($voucher['emisor']->Nombre))?$voucher['emisor']->Nombre:"" }}</td>    
                    <td>{{ $voucher["moneda"] }}</td>
                    <td>{{ $voucher["total"] }}</td>
                    <td>{{ $voucher["clave"] }}</td>
                </tr>
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
<!-- #modal-addDocument -->
<div class="modal fade" id="modal-importVoucher">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" >Importar Comprobante </h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ route('importVoucher') }}" method="POSt" id="import_v" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Clave: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input  class="form-control" type="number"  name="key" id="key"  min="50" data-parsley-required="true"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">XML Factura: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input type="file" accept="text/xml" name="v_xml" class="form-control" data-parsley-required="true" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">XML Respuesta: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input type="file" accept="text/xml" name="v_xmlr" class="form-control" data-parsley-required="true" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">PDF Factura: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input type="file" accept="application/pdf" name="v_pdf" class="form-control" data-parsley-required="true" required/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="import_v" class="btn btn-success">Importar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<!-- #modal-addDocument -->
<div class="modal fade" id="modal-proccessVoucher">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" >Aceptación de Comprobante </h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="" method="POST" id="procces_v" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Actividad economica: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <select class="form-control" data-parsley-required="true" name="id_ea" id="id_ea" id="select-required">
                                @foreach($e_as as $e_a) 
                                <option style="color: black;" value="{{ $e_a->id }}">{{ $e_a->name_ea }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <select onchange="changeBO(this.value)" class="form-control" data-parsley-required="true" name="id_branch_office" id="id_branch_office" id="select-required">
                                @foreach($branch_offices as $branch_office)
                                @if($branch_office->number == "001")
                                <option selected="true" style="color: black;" value="{{ $branch_office->id }}" >{{ $branch_office->name_branch_office }}</option>
                                @else
                                <option style="color: black;" value="{{ $branch_office->id }}">{{ $branch_office->name_branch_office }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>    
                     <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Categoria: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <select class="form-control" data-parsley-required="true" name="category" id="category" id="select-required">
                                <option style="color: black;" value="Bienes">Sin Clasificar</option>
                                <option style="color: black;" value="Bienes">Bienes</option>
                                <option style="color: black;" value="Bienes Capital">Bienes Capital</option>
                                <option style="color: black;" value="Servicios">Servicios</option>
                                <option style="color: black;" value="Exento">Exento</option>
                                <option style="color: black;" value="No sujeto">No Sujeto</option>
                                <option style="color: black;" value="Fuera de la actividad economica">Fuera de la Actividad Económica</option>
                            </select>
                        </div>
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="procces_v" class="btn btn-success">Procesar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<!-- #modal-addDocument -->
<div class="modal fade" id="modal-proccessVoucherR">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" >Rechazo de Comprobante </h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="" method="POST" id="procces_vr" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Actividad economica: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <select class="form-control" data-parsley-required="true" name="id_ea" id="id_ea" id="select-required">
                                @foreach($e_as as $e_a) 
                                <option style="color: black;" value="{{ $e_a->id }}">{{ $e_a->name_ea }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <select onchange="changeBO(this.value)" class="form-control" data-parsley-required="true" name="id_branch_office" id="id_branch_office" id="select-required">
                                @foreach($branch_offices as $branch_office)
                                @if($branch_office->number == "001")
                                <option selected="true" style="color: black;" value="{{ $branch_office->id }}" >{{ $branch_office->name_branch_office }}</option>
                                @else
                                <option style="color: black;" value="{{ $branch_office->id }}">{{ $branch_office->name_branch_office }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>    
                     <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Motivo de rechazo: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <textarea class="form-control" rows="3" name="observation" id="observation" data-parsley-required="true"></textarea>
                        </div>
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="procces_vr" class="btn btn-success">Procesar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<!-- #modal-viewExpense -->
<div class="modal fade" id="modal-viewExpense">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="t_dView"> </h4> 
                Vista Generada segun el xml del documento electrónico
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                
                    <!-- begin invoice -->
                    <div class="invoice">

                        <!-- begin invoice-header -->
                        <div class="invoice-header">
                            <div class="invoice-from">
                                <address class="m-t-5 m-b-5">
                                    <h5 > Emisor:</h5> 
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse" id="cedulaE" name="cedulaE"></h6> 
                                    <h6 class="text-inverse" id="nombreE" name="nombreE"></h6> 
                                    <h6 class="text-inverse" id="telefonoE" name="telefonoE"></h6> 
                                    <h6 class="text-inverse" id="correoE" name="correoE"></h6> 
                                    
                                </address>
                                    <!-- end form-group -->
                            </div>
                              <div class="invoice-to">                                
                                <address class="m-t-5 m-b-5">
                                    <h5 > Receptor:</h5> 
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse" id="cedulaR" name="cedulaR"></h6> 
                                    <h6 class="text-inverse" id="nombreR" name="nombreR"></h6> 
                                    <h6 class="text-inverse" id="telefonoR" name="telefonoR"></h6> 
                                    <h6 class="text-inverse" id="correoR" name="correoR"></h6> 
                                    
                                </address>
                            </div>      
                            <div class="invoice-date">                                
                                <address class="m-t-5 m-b-5">
                                    <h5 id="consecutiveView" name="consecutiveView"> consecutivo:</h5> 
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse" id="keyView" name="keyView"></h6> 
                                    <h6 class="text-inverse" id="dateView" name="dateView"></h6> 
                                    <h6 class="text-inverse" id="sale_conditionView" name="sale_conditionView"></h6> 
                                    <h6 class="text-inverse" id="payment_methodView" name="payment_methodView"></h6> 
                                    <h6 class="text-inverse" id="currencyView" name="currencyView"></h6>
                                </address>
                            </div>
                        </div>
                        <!-- end invoice-header -->
                        <!-- begin invoice-content -->
                        <div class="invoice-content">
                            <!-- begin table-responsive -->
                            <div class="table-responsive">
                                <table class="table table-invoice" >
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="1%">N°</th>
                                            <th class="text-center" width="10%">CABYS</th>
                                            <th class="text-center" width="25%">DESCRIPCION</th>
                                            <th class="text-center" width="7%">UNIDAD</th>
                                            <th class="text-center" width="7%">CANTIDAD</th>
                                            <th class="text-center" width="25%">PRECIO UNID.</th>
                                            <th class="text-center" width="7%">DECUENTO</th>
                                            <th class="text-center" width="7%">IMPUESTOS</th>
                                            <th class="text-center" width="7%">EXONERACION</th>
                                            <th class="text-center" width="7%">TOTAL</th>
                                            <th class="text-center" width="4%"></th>
                                            <th class="text-center" width="4%" hidden="true"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="DetalleServicioView" name="DetalleServicioView">

                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                            <!-- begin invoice-price -->
                            <div class="invoice-price">
                                <div class="invoice-price-left">
                                    <div class="invoice-price-row">
                                        <div class="sub-price">
                                            <small>SUBTOTAL</small>
                                            <span class="text-inverse" id="sub_totalView"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price">
                                            <small>DESCUENTO</small>
                                            <span class="text-inverse" id="total_discountView"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-plus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price">
                                            <small>IMPUESTO</small>
                                            <span class="text-inverse" id="total_taxView"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price text-small">
                                            <small>EXONERADO</small>
                                            <span class="text-inverse text-small" id="total_exonerationView"><h5>0.00</h5></span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="invoice-price-left">
                                    <small>TOTAL</small> <span class="f-w-400" id="total_invoiceView">0.00</span>
                                </div>
                            </div>
                            <!-- end invoice-price -->
                        </div>
                        <!-- end invoice-content -->
                        <!-- begin invoice-note -->
                        <div class="invoice-note">
                            <!-- begin panel -->
                            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">

                                <!-- begin panel-body -->
                                <div class="panel-body panel-form">
                                    <div class="form-group row">
                                        
                                    </div>
                                </div>
                                <!-- end panel-body -->
                            </div>
                            <!-- end panel -->
                        </div>
                        <!-- end invoice-note -->
                        <!-- begin invoice-footer -->
                        <div class="invoice-footer">
                            <p class="text-center m-b-5 f-w-600">
                                GRACIAS POR USAR NUESTROS SISTEMAS
                            </p>
                            <p class="text-center">
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> facturarapida.net</span>
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:8399-6444</span>
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> info@facturarapida.net</span>
                            </p>
                        </div>
                        <!-- end invoice-footer -->
                    </div>
                    <!-- end invoice -->
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>

            </div>
        </div>
    </div>
</div>
<!-- #modal-viewExpense -->

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
<script src="{{ asset('admin/js/voucher.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('admin/plugins/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
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

