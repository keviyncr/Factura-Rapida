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
        <h4 class="panel-title">Listado de Gastos</h4>
        <div class="panel-heading-btn">
       
            <div class="btn-group m-r-5 m-b-5">
                    <form data-parsley-validate="true" action="{{ url('/expenses') }}" method="GET">
            <div class="panel-heading-btn">
            <!--agregar los rango-->
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
             
				<div class=" row row-space-12">
					<div class="col-lg-12 row row-space-12">
                        <label class="col-lg-1 col-form-label">Filtrar:</label>
						<div class="col-xs-3 mb-2 mb-sm-0">
						    <input type="text" class="form-control" id="datepicker-f1" name="f1" placeholder="Fecha Inicio" value="{{ $fecha1 = (isset($f1))?$f1:date('Y-m-d') }}"  />
						</div>
						<div class="col-xs-3  mb-2 mb-sm-0">
							<input type="text" class="form-control" id="datepicker-f2" name="f2" placeholder="Fecha Final" value="{{ $fecha2 = (isset($f2))?$f2:date('Y-m-d') }}" />
						</div>
                        <div class="col-xs-3  mb-2 ">
							<input type="submit" class="form-control btn btn-danger" name="btn1" value = "Filtrar" />
						</div>
					</div>
				</div>
            </div>
        </form>
            </div>
            @if( Auth::user()->roll != "SuperGlovers" )
            <div class="btn-group m-r-5 m-b-5">
                <a href="javascript:onclick=newExpense('08',{{ $bo }});" class="btn btn-danger">Factura Electronica de Compra</a>
            </div>
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
                    <th data-orderable="false">Acciones</th>
                    <th  class="text-nowrap">fecha</th>
                    <th  class="text-nowrap">Consecutive</th>
                    <th  class="text-nowrap">Proveedor</th>                   
                    <th  class="text-nowrap">Total</th>  
                    <th  class="text-nowrap">Condicion</th> 
                    <th  class="text-nowrap">Estado MH</th> 
                     <th  class="text-nowrap">QB</th> 
                    <th  class="text-nowrap">Categoria</th>
                    <th  class="text-nowrap">Detalle MH</th>
                    <th  class="text-nowrap">Actividad Economica</th>
                    <th class="text-nowrap">Clave</th>
                </tr>
            </thead>
            <tbody>
                @php  $cont = 1 @endphp
                @if(isset($expenses))
                @foreach($expenses as $expense)
                <tr class="gradeU">
                    <td width="1%" >{{ $cont++ }}</td>
                    <td width="20%">                        
                        <div class="btn-group btn-group-justified">
                            <a href="{{ asset('laravel/storage/app/public/'.$expense->ruta.$expense->key.'.xml') }}" download="{{ $expense->key }}" title="XML de Factura" class="btn btn-default"><i class="">XML</i></a>
                            @if(file_exists(  'laravel/storage/app/public/'.$expense->ruta.$expense->key.'-R.xml'  ))
                            <a href="{{ asset('laravel/storage/app/public/'.$expense->ruta.$expense->key.'-R.xml') }}" download="{{ $expense->key.'-R' }}" title="XML de Factura" class="btn btn-default"><i class="">XML-R</i></a>
                            @endif
                            @if(file_exists( 'laravel/storage/app/public/'.$expense->ruta.$expense->key.'.pdf'  ))
                            <a href="{{ asset('laravel/storage/app/public/'.$expense->ruta.$expense->key.'.pdf') }}" target="_blank" class="btn btn-default"><i title="Vista del Documento" class="fa fa-search"></i></a>
                            @else
                            <a href="javascript:onclick=viewExpense('{{ $expense->key }}');" class="btn btn-default"><i title="Vista de Factura" class="fa fa-search"></i></a>
                            @endif
                            @if(file_exists( 'laravel/storage/app/public/'.$expense->ruta.$expense->key.'-A.pdf'  ))
                            <a href="{{ asset('laravel/storage/app/public/'.$expense->ruta.$expense->key.'-A.pdf') }}" target="_blank" class="btn btn-default"><i title="Vista del Estado" class="fa fa-file-alt"></i></a>
                            @endif
                            @if(session('company')->qb)
                            <a href="{{ url('saveEQB/'.$expense->key) }}" class="btn btn-default"><i title="Guardar en Quickbooks"  class="">QB</i></a>
                            @endif
                        </div>
                    </td>
                    <td width="10%">{{ '20'.substr($expense->key, 7, 2).'-'.substr($expense->key, 5, 2).'-'.substr($expense->key, 3, 2) }}</td>
                    <td width="15%">{{ $expense->consecutive }}</td>
                    <td width="20%">{{ $expense->provider }}</td>            
                    <td width="5%">{{ $expense->total_invoice }}</td>
                    @if($expense->condition == "aceptado" )
                    <td width="5%" class="text-center"><i title="Aceptado" class="fa fa-check-circle text-green-darker"></i></td>
                    @elseif($expense->condition == "rechazado" )
                    <td width="5%" class="text-center"><i title="Rechazado" class="fa fa-times-circle text-red-darker"></i></td>
                    @elseif($expense->condition == "guardado" )
                    <td width="5%" class="text-center"><i title="Solo guardado" class="fa fa-save text-blue"></i></td>
                    @endif
                     @if($expense->state == "aceptado" )
                    <td width="5%" class="text-center"><i title="Aceptado" class="fa fa-check-circle text-green-darker"></i></td>
                    @elseif($expense->state == "rechazado" )
                    <td width="5%" class="text-center"><i title="Rechazado" class="fa fa-times-circle text-red-darker"></i></td>
                    @elseif($expense->state == "guardado" )
                    <td width="5%" class="text-center"><i title="Solo guardado" class="fa fa-save text-blue"></i></td>
                     @else
                    <td width="5%" class="text-center">
                        <a href="{{ route('consultStateE',$expense->key) }}" ><i title="Procesando, Click para actualizar!" class="fa fa-sync-alt text-blue-darker"></i></a>
                    </td>
                    @endif
                    <td width="15%">{{ $expense->qb }}</td>
                    <td width="13%">
                    <select onchange='changeCategory(this.value,"{{ $expense->id }}")' class="form-control" data-parsley-required="true" name="categoryS" id="categoryS" >
                        <option style="color: black;" value="Sin Clasificar" {{ $c=($expense->category == 'Sin Clasificar')?'selected':'' }} >Sin Clasificar</option>
                        <option style="color: black;" value="Bienes" {{ $c=($expense->category == "Bienes")?"selected":"" }} >Bienes</option>
                        <option style="color: black;" value="Bienes Capital" {{ $c=($expense->category == "Bienes Capital")?"selected":"" }} >Bienes Capital</option>
                        <option style="color: black;" value="Servicios" {{ $c=($expense->category == "Servicios")?"selected":"" }} >Servicios</option>
                        <option style="color: black;" value="Exento" {{ $c=($expense->category == "Exento")?"selected":"" }} >Exento</option>
                        <option style="color: black;" value="No sujeto" {{ $c=($expense->category == "No sujeto")?"selected":"" }} >No sujeto</option>
                        <option style="color: black;" value="Fuera de la actividad economica" {{ $c=($expense->category == "Fuera de la actividad economica")?"selected":"" }} >Fuera de la actividad economica</option>
                    </select>
                    </td>
                    <td width="15%">{{ $expense->detail_mh }}</td>
                    <td width="15%">{{ $expense->e_a }}</td>
                    <td width="16%">{{ 'C'.$expense->key }}</td>
                    
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
<!-- #modal-addExpense -->
<div class="modal fade" id="modal-addExpense">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="t_d"> </h4> 
                <input name="type_expense" id="type_expense" hidden="true">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="javascript:storeExpense()" name="add_expense" id="add_expense" class="form-horizontal form-bordered">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <!-- begin invoice -->
                    <div class="invoice">

                        <!-- begin invoice-header -->
                        <div class="invoice-header">
                            <div class="invoice-from">
                                <address class="m-t-5 m-b-5">
                                    Facturar de:<br>
                                    <!-- begin form-group -->
                                    <select onchange="getClient(this.value)" class="default-select2 form-control" data-parsley-required="true" name="id_provider" id="id_provider" id="select-required">
                                        <option  style="color: black;" value="">Seleccionar...</option>
                                        @foreach($providers as $provider)                                       
                                        <option style="color: black;" value="{{ $provider->id }}">{{ $provider->name_provider }}</option>
                                        @endforeach
                                    </select> <br>   
                                    Moneda: 
                                    <select class="form-control" data-parsley-required="true" name="id_currency" id="id_currency" id="select-required">
                                        @foreach($currencies as $currency) 
                                        @if($currency->id == 55)
                                        <option selected="true" style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $currency->id }}">{{ $currency->currency." - ".$currency->code }}</option>
                                        @endif
                                        @endforeach
                                    </select><br>
                                    Medio de pago:
                                    <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_method" id="select-required">
                                        @foreach($payment_methods as $payment_method)                                       
                                        <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                        @endforeach
                                    </select> <br>
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5">
                                    Condicion compra:
                                    <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_condition" id="select-required">
                                        @foreach($sale_conditions as $sale_condition)                                       
                                        <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                        @endforeach
                                    </select>   <br>
                                    Plazo credito:
                                    <input  class="form-control" type="number"  name="time" id="time" value="1" min="1" max="99" data-parsley-required="true"/>
                                    <br> 
                                </address>
                            </div>
                            <div class="invoice-date">                                
                                <address class="m-t-5 m-b-5">
                                    Consecutivo:
                                    <input disabled="true" class="form-control" type="number"  name="consecutive" id="consecutive" data-parsley-required="true"/>
                                    <br>
                                    Actividad Economica: 
                                    <select class="form-control" data-parsley-required="true" name="id_ea" id="id_ea" id="select-required">
                                        @foreach($e_as as $e_a) 
                                        <option style="color: black;" value="{{ $e_a->id }}">{{ $e_a->name_ea }}</option>
                                        @endforeach
                                    </select><br>
                                    Sucursal: 
                                    <select onchange="changeBO(this.value)" class="form-control" data-parsley-required="true" name="id_branch_office" id="id_branch_office" id="select-required">
                                        @foreach($branch_offices as $branch_office)
                                        @if($branch_office->number == "001")
                                        <option selected="true" style="color: black;" value="{{ $branch_office->id }}" >{{ $branch_office->name_branch_office }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $branch_office->id }}">{{ $branch_office->name_branch_office }}</option>
                                        @endif
                                        @endforeach
                                    </select><br>
                                </address>
                            </div>
                        </div>
                        <!-- end invoice-header -->
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">

                            <!-- begin panel-body -->
                            <div class="panel-body panel-form">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Producto o Servicio</label>
                                    <div class="col-lg-8">
                                        <input  class="form-control" type="text"  name="detail" id="detail" data-parsley-required="true"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <label class="col-lg-4 col-form-label">CAByS</label>
                                        <input  class="form-control" type="text"  name="cabys" id="cabys"  placeholder='0000000000000' data-parsley-required="true"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-4 col-form-label">Cantidad</label>
                                        <input  class="form-control" type="number"  name="qty" id="qty" value="1" step='0.01'  placeholder='0.00' data-parsley-required="true"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Precio Unidad</label>
                                        <input  class="form-control" type="number"  name="priceU" id="priceU" step='0.00001'  placeholder='0.000000' data-parsley-required="true"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Unidad de Medida</label>
                                        <select class="default-select2 form-control" data-parsley-required="true" name="id_sku" id="id_sku" id="select-required">
                                            @foreach($skuses as $sku)                                       
                                            <option style="color: black;" value="{{ $sku->id }}">{{ $sku->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                               
                                </div>
                                <div class="form-group row">
                                         <div class="col-lg-4">
                                        <label class="col-lg-4 col-form-label">Descuento</label>
                                        <input  class="form-control" type="number"  name="discount" id="discount" value="0" />
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="col-lg-4 col-form-label">Impuestos</label>
                                        <select  class="default-select2  form-control" name="id_tax" id="id_tax" placeholder="Impuestos" >
                                             <option style="color: black;"  value="0" >Ninguno</option>
                                            @foreach($taxes as $tax)                                       
                                            <option style="color: black;"  value="{{ $tax->id }}" >{{ $tax->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="col-lg-4 col-form-label"></label>
                                        <br>
                                        <button type="button" onclick="addLine()" class="btn btn-success col-lg-12">Agregar Linea</button>
                                    </div>
                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>
                        <!-- end panel -->
                        <!-- begin invoice-content -->
                        <div class="invoice-content">
                            <!-- begin table-responsive -->
                            <div class="table-responsive">
                                <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
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
                                    <tbody>

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
                                            <span class="text-inverse" id="sub_total"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price">
                                            <small>DESCUENTO</small>
                                            <span class="text-inverse" id="total_discount"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-plus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price">
                                            <small>IMPUESTO</small>
                                            <span class="text-inverse" id="total_tax"><h5>0.00</h5></span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price text-small">
                                            <small>EXONERADO</small>
                                            <span class="text-inverse text-small" id="total_exoneration"><h5>0.00</h5></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-price-right">
                                    <small>TOTAL</small> <span class="f-w-600" id="total_invoice">0.00</span>
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
                                        <label class="col-lg-4 col-form-label">Tipo de documento de referencia</label>
                                        <div class="col-lg-8">
                                            <select class="default-select2 form-control"   name="id_reference_type_expense" onchange="reference()" id="id_reference_type_expense" >
                                                <option  style="color: black;"  value="">No posee referencia...</option>
                                                @foreach($reference_type_expenses as $rtd)                                       
                                                <option style="color: black;"  value="{{ $rtd->code }}">{{ $rtd->document }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="reference" hidden="true">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Clave o consecutivo</label>
                                            <div class="col-lg-8">
                                                <input  class="form-control" type="number"  name="numReference" id="numReference" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Fecha de Emisión</label>
                                            <div class="col-lg-8">
                                                <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                                    <input type="text" class="form-control" id="datepicker-autoClose" name="date_reference" placeholder="Fecha de emisión" />
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Codigo Referencia</label>
                                            <div class="col-lg-8">
                                                <select class="default-select2 form-control"   name="code_reference" id="code_reference" >
                                                    @foreach($reference_codes as $rc)                                       
                                                    <option style="color: black;" value="{{ $rc->code }}">{{ $rc->description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Razón</label>
                                            <div class="col-lg-8">
                                                <input  class="form-control" type="text"  name="reason" id="reason" />
                                            </div>
                                        </div>
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

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_expense"  class="btn btn-success">Procesar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addExpense -->
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
<script src="{{ asset('admin/js/expense.js') }}"></script>
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

