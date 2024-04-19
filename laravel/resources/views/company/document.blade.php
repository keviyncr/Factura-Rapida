@extends('layouts.default')
@section('title', 'Documentos')

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
<link href="{{ asset('admin/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/@danielfarrell/bootstrap-combobox/css/bootstrap-combobox.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/tag-it/css/jquery.tagit.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" />
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
        <h4 class="panel-title">Listado de documentos</h4>
      
        <div class="panel-heading-btn">
            <div class="btn-group m-r-5 m-b-5">
                <form data-parsley-validate="true" action="{{ url('/documents') }}" method="GET">
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
                <a href="javascript:onclick=newDocument('01',{{ $bo }});" class="btn btn-danger">Factura Electronica</a>
                <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><b class="caret"></b></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:onclick=newDocument('04',{{ $bo }});" class="dropdown-item">Tiquete Electronico</a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:onclick=newDocument('02',{{ $bo }});" class="dropdown-item">Nota de Debito</a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:onclick=newDocument('03',{{ $bo }});" class="dropdown-item">Nota de Credito</a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:onclick=newDocument('09',{{ $bo }});" class="dropdown-item">Factura de Exportacion</a>
                </div>
            </div>
            @endif
            
        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
        <div class="panel-body" width="100%" style="font-size:10px">
        <table id="data-table-products" class="table table-striped table-bordered table-td-valign-middle" width="100%">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="30%" data-orderable="false">Acciones</th>
                    <th width="12%" class="text-nowrap">Fecha</th>  
                    <th width="10%" class="text-nowrap">Consecutive</th>
                    <th width="20%" class="text-nowrap">Cliente</th>                   
                    <th width="5%" class="text-nowrap">Total</th>  
                    <th width="5%" class="text-nowrap">Envio</th>
                     @if(session('company')->qb)
                     <th width="5%" class="text-nowrap">Estado MH</th>
                     @endif
                    <th width="5%" class="text-nowrap">Estado QB</th> 
                    <th width="15%" class="text-nowrap">Clave</th>
                    <th width="16%" class="text-nowrap">Detalle MH</th>
                </tr>
            </thead>
            <tbody>
                @php  $cont = 1 @endphp
                @if(isset($documents))
                @foreach($documents as $document)
                <tr class="gradeU">
                    <td width="1%" class="text-center">{{ $cont++ }}</td>
                    <td width="15%" class="text-center">                        
                        <div class="btn-group btn-group-justified">
                            <a href="{{ asset('laravel/storage/app/public/'.$document->ruta.$document->key.'.xml') }}" download="{{ $document->key }}" title="XML de Factura" class="btn btn-default"><i class="">XML</i></a>
                            @if(file_exists(  'laravel/storage/app/public/'.$document->ruta.$document->key.'-R.xml'  ))
                            <a href="{{  asset('laravel/storage/app/public/'.$document->ruta.$document->key.'-R.xml') }}" download="{{ $document->key.'-R' }}" title="XML de Factura" class="btn btn-default"><i class="">XML-R</i></a>
                            @endif
                            <a href="{{  asset('laravel/storage/app/public/'.$document->ruta.$document->key.'.pdf') }}" target="_blank" class="btn btn-default"><i title="Vista de Factura" class="fa fa-search"></i></a>
                            <a href="{{ url('printTicket/'.$document->key) }}" target="_blank" class="btn btn-default"><i title="Imprimir Tiquete"  class="fa fa-print"></i></a>
                            @if(session('company')->qb)
                            <a href="{{ url('saveQB/'.$document->key.'/'.$document->answer_mh) }}" class="btn btn-default"><i title="Guardar en Quickbooks"  class="">QB</i></a>
                            @endif
                        </div>
                    </td>
                    <td width="8%" class="text-center">{{ substr($document->created_at, 0, 10) }}</td>
                    <td width="10%" class="text-center">{{ $document->consecutive }}</td>
                    <td width="15%" class="text-center">{{ $document->client }}</td>      
                    <td width="10%" class="text-center">{{ $document->total_invoice }}</td>
                    @if($document->state_send == "Enviado" )
                    <td width="5%" class="text-center">
                       <a href="javascript:onclick=sendInvoicecc('{{ $document->key }}','{{ $document->client_mail }}');" ><i title="Enviado, Click para reenviar!" class="fa fa-paper-plane text-green-darker"></i></a>
                    </td>
                    @else
                    <td width="5%" class="text-center">
                         <a href="javascript:onclick=sendInvoicecc('{{ $document->key }}','{{ $document->client_mail }}');" ><i title="No enviado, Click para enviar!" class="fa fa-paper-plane text-red-darker"></i></a>
                    </td>
                    @endif
                    @if($document->answer_mh == "aceptado" )
                    <td width="5%" class="text-center"><i title="Aceptado" class="fa fa-check-circle text-green-darker"></i>
                    
                    </td>
                    @elseif($document->answer_mh == "rechazado" )
                    <td width="5%" class="text-center"><i title="Rechazado" class="fa fa-times-circle text-red-darker"></i></td>
                    @else
                    <td width="5%" class="text-center">
                        <a href="{{ route('consultState',$document->key) }}" ><i title="Procesando, Click para actualizar!" class="fa fa-sync-alt text-blue-darker"></i></a>
                    </td>
                    @endif
                    @if(session('company')->qb)
                     <th width="5%" class="text-nowrap">{{ $document->state_mh }}</th>
                     @endif
                    <td width="15%" class="text-center">{{ 'D'.$document->key }}</td>
                    <td width="15%" class="text-center">{{ $document->detail_mh }}</td>
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
<div class="modal fade" id="modal-sendInvoice">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" > </h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <form  action="{{ url('/sendInvoicecc') }}" method="POST" name="send_document" id="send_document" class="form-horizontal form-bordered">
                    @csrf
                    
                    <!-- begin form-group -->
                    <div hidden='true' class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Documento a enviar: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="key_send" id="key_send" data-parsley-required="true" class="form-control" placeholder="Clave de documento a enviar" />
                        </div>                       
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Envia a: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="mail_send" id="mail_send" data-parsley-required="true" class="form-control" placeholder="Envia a esta direccion" />
                        </div>                       
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">CC a: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="cc_mail" id="cc_mail"  class="form-control" placeholder="Enviar copia a..." />
                        </div>                       
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="send_document" class="btn btn-success">Enviar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<!-- #modal-addDocument -->
<div class="modal fade" id="modal-addDocument">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="t_d"> </h4> 
                <input name="type_document" id="type_document" hidden="true">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="javascript:storeDocument()" name="add_document" id="add_document" class="form-horizontal form-bordered">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <!-- begin invoice -->
                    <div class="invoice">

                        <!-- begin invoice-header -->
                        <div class="invoice-header">
                            <div class="invoice-from">
                                <address class="m-t-5 m-b-5">
                                    Facturar a:<br>
                                    <!-- begin form-group -->
                                    <select onchange="getClient(this.value)" class="default-select2 form-control" data-parsley-required="true" name="id_client" id="id_client" id="select-required">
                                        <option  style="color: black;" value="">Seleccionar...</option>
                                        @foreach($clients as $client)                                       
                                        <option style="color: black;" value="{{ $client->id }}">{{ $client->name_client }}</option>
                                        @endforeach
                                    </select> <br>   
                                    Moneda: 
                                    <select class="form-control" data-parsley-required="true" name="id_currency" id="id_currency" id="select-required">
                                        @foreach($currencies as $currency) 
                                        @if($currency->id == '55')
                                        <option selected="true" style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @endif
                                        @endforeach
                                    </select><br>
                                    Condicion venta:
                                    <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_condition" id="select-required">
                                        @foreach($sale_conditions as $sale_condition)                                       
                                        <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                        @endforeach
                                    </select>   <br>
                                     <div hidden id='w1'>
                                     GLN Sucursal:
                                     <input  class="form-control" type="text"  name="gln" id="gln"   />
                                    <br>
                                    </div>
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5">
                                      Medio de pago:
                                    <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_method" id="select-required">
                                        @foreach($payment_methods as $payment_method)        
                                        <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                        @endforeach
                                    </select> <br>
                                   Tipo de cambio:
                                    <input  class="form-control" type="number"  name="type_change" id="type_change" step='0.01' value='1.00' placeholder='0.00' data-parsley-required="true"/>
                                    <br> 
                                    Plazo credito:
                                    <input  class="form-control" type="number"  name="time" id="time" value="1" min="0" max="99" data-parsley-required="true"/>
                                    <br> 
                                    <div hidden id='w2'>
                                    Orden de entrega:
                                   <input  class="form-control" type="text"  name="order" id="order" /><br>
                                   </div>
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
                                        <select class="default-select2 form-control" onchange="getProduct()"  name="id_product" id="id_product" >
                                            <option  style="color: black;" value="0">Seleccionar...</option>
                                            @foreach($products as $product)                                       
                                            <option style="color: black;" value="{{ $product->id }}">{{ $product->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                     <div class="col-lg-4">
                                        <label class="col-lg-4 col-form-label">Description</label>
                                        <input  class="form-control" type="text"  name="detailL" id="detailL" />
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-4 col-form-label">Cantidad</label>
                                        <input  class="form-control" type="number"  name="qty" id="qty" step='0.01'  placeholder='0.000000' value="1"/>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Precio</label>
                                        <input  class="form-control" type="number"  name="priceL" id="priceL" value="0" step='0.00001'  placeholder='0.000000' />
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Descuento</label>
                                        <input  class="form-control" type="number"  name="discountL" id="discountL" value="0" step='0.00001'  placeholder='0.000000'/>
                                    </div>
                                    <div class="col-lg-1">
                                        <label class="col-lg-12 col-form-label">IVI</label>
                                        <input  class="form-control" type="checkbox"  name="ivi" id="ivi" />
                                    </div>
                                    <div class="col-lg-1">
                                        <label class="col-lg-4 col-form-label"></label>
                                        <br>
                                        <button tittle= "Agregar Linea" type="button" onclick="addLine()" class="btn btn-success col-lg-12">+ </button>
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
                                            <th class="text-center" width="10%">CODIGO</th>
                                             <th class="text-center" width="10%">CABYS</th>
                                            <th class="text-center" width="25%">DESCRIPCION</th>
                                            <th class="text-center" width="7%">UNIDAD</th>
                                            <th class="text-center" width="7%">CANTIDAD</th>
                                            <th class="text-center" width="10%">PRECIO UNID.</th>
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
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>                                        
                                        <div class="sub-price text-small">
                                            <small>IVA Devuelto</small>
                                            <span class="text-black text-small" id="iva_devuelto"><h5> <input  class="form-control" type="number"  name="ivaReturn" id="ivaReturn" value="0" step='0.00001'  placeholder='0.000000' disabled /></h5></span>
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
                         <!-- otherchages -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <div class="panel-heading">
                                <h4 class="panel-title">Otros Cargos</h4>
                                <div class="panel-heading-btn">
                                    <button type="button" class="btn btn-red col-lg-12" onclick="otherCharge()">Agregar otros cargos</button>
                                </div>
                            </div>
                            <!-- begin panel-body -->
                            <div id="othercharges" hidden class="panel-body panel-form">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="col-md-12 col-form-label">Tipo de documento </label>
                                        <select class="form-control" name="id_type_document_other" id="id_type_document_other" >
                                            <option style="color: black;" value="">Seleccionar...</option>
                                            @foreach($allTypeDocumentOtherCharges as $typedoc)
                                            <option style="color: black;" value="{{ $typedoc->code }}">{{ $typedoc->type_document }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_type_document_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Cedula de Tercero</label>
                                        <input class="form-control" type="number" name="idcard_other" id="idcard_other" placeholder='de 9 a 12 digitos'  />
                                        @error('idcard_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-5">
                                        <label class="col-lg-12 col-form-label">Nombre de Tercero</label>
                                        <input class="form-control" type="text" name="name_other" id="name_other" placeholder='Nombre de Tercero'/>
                                        @error('name_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <label class="col-lg-12 col-form-label">Detalle del cargo</label>
                                        <input class="form-control" type="text" name="detail_other_charge" id="detail_other_charge" placeholder='Detalle del cargo adicional' />
                                        @error('detail_other_charge') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Monto</label>
                                        <input class="form-control" type="number" name="amount_other" id="amount_other" value="0" />
                                        @error('amount_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-4 col-form-label"></label>
                                        <br>
                                        <button tittle= "Agregar Cargo" type="button" onclick="addLineOtro()" class="btn btn-success col-lg-12">Agregar Cargo </button>
                                    </div>
                                </div>
                                <!-- begin invoice-content -->
                                <div class="invoice-content">
                                    <!-- begin table-responsive -->
                                    <div class="table-responsive bg-gray-400">
                                        <table class="table table-other" id="DetalleOtherServicio" name="DetalleOtherServicio">
                                            <thead>
                                                <tr class="bg-black  text-white">
                                                <th class="text-center" width="5%"></th>
                                                    <th class="text-center" width="1%">N°</th>
                                                    <th class="text-center" width="20%">TIPO CARGO</th>
                                                    <th class="text-center" width="15%">CEDULA TERCERO</th>
                                                    <th class="text-center" width="24%">NOMBRE TERCERO</th>
                                                    <th class="text-center" width="25%">DESCRIPCION CARGO</th>
                                                    <th class="text-center" width="10%">MONTO</th>
                                                    <th class="text-center" width="10%" hidden="true"></th>
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
                                                    <small>TOTAL OTROS CARGOS</small>
                                                    <span class="text-inverse">
                                                        <h5  id="total_other_charges"> 0.00</h5>
                                                    </span>
                                                </div>
                                                <div class="sub-price">
                                                    <i class="fa fa-plus text-muted"></i>
                                                </div>
                                                <div class="sub-price">
                                                    <small>PRODUCTOS O SERVICIOS</small>
                                                    <span class="text-inverse" id="">
                                                        <h5  id="total_ps">0.00</h5>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invoice-price-right">
                                            <small>TOTAL DOCUMENTO</small> <span class="f-w-600" id="total_invoiceO">0.00</span>
                                        </div>
                                    </div>
                                    <!-- end invoice-price -->
                                </div>
                                <!-- end invoice-content -->
                            </div>
                            <!-- end panel-body -->
                        </div>
                        <!-- end otherchages -->
                        <!-- begin invoice-note -->
                        <div class="invoice-note">
                            <!-- begin panel -->
                            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">

                                <!-- begin panel-body -->
                                <div class="panel-body panel-form">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Tipo de documento de referencia</label>
                                        <div class="col-lg-8">
                                            <select class="default-select2 form-control"   name="id_reference_type_document" onchange="reference()" id="id_reference_type_document" >
                                                <option  style="color: black;" value="">No posee referencia...</option>
                                                @foreach($reference_type_documents as $rtd)                                       
                                                <option style="color: black;" value="{{ $rtd->code }}">{{ $rtd->document }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="reference" hidden="true">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Clave o consecutivo</label>
                                            <div class="col-lg-8">
                                                <input  class="form-control" type="text"  name="numReference" id="numReference" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Fecha de Emisión</label>
                                            <div class="col-lg-8">
                                                <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" >
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
                <button type="submit" form="add_document" class="btn btn-success">Procesar</button>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/plugins/parsleyjs/dist/parsley.js') }}"></script>
<script src="{{ asset('admin/plugins/smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('admin/js/app/wizards-validation.js') }}"></script>
<script src="{{ asset('admin/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('admin/plugins/select2/dist/js/select2.js') }}"></script>
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
<script src="{{ asset('admin/js/document.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/plugins/select2/dist/js/select2.min.js') }}"></script>


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
<script>
  $(document).ready(function(){
    	$("#id_payment_method").change(function(){
                if($('select[id=id_payment_method]').val() == 2){
                 document.getElementById("ivaReturn").disabled = false;
                }else{
                 document.getElementById("ivaReturn").disabled = true;
                }
    	});
    	$("#id_type_document_other").change(function(){
    	    
    	    if(document.getElementById("id_type_document_other").value == '06'){
             document.getElementById("amount_other").value = parseFloat(document.getElementById("sub_total").innerHTML*0.10).toFixed(2);
             
            }
    	});
    	$("#ivaReturn").change(function(){
            if(document.getElementById("ivaReturn").val() != ''){
             document.getElementById("total_invoice").value = document.getElementById("total_invoice").val()-document.getElementById("ivaReturn").val();
            }
    	});
    		
    	
    });  
    
  
</script>


@endpush

