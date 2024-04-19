@extends('layouts.app')
@section('title', 'Reporte IVA')

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
<div class="profile">
    <div class="profile-header">
        <!-- BEGIN profile-header-cover -->
        <div class="profile-header-conver">
            <h4 class="panel-title">Reporte IVA</h4>
        </div>
        <!-- END profile-header-cover -->
        <!-- BEGIN profile-header-content -->
        <div class="profile-header-content" style="background-color:black;">
            <form data-parsley-validate="true" action="{{ url('/ivaReport') }}" method="POST">
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
                                @foreach($economic_activities as $economic_activity)
                                    <option style="color: black;" value="{{ $economic_activity->number }}">{{ $economic_activity->number." - ".$economic_activity->name_ea }}</option>  
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
@if(isset($bienes) && count($bienes) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Bienes</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports1" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>       
                    <th width="7%" data-orderable="false">Otros Cargos</th>  
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th>
                    <th width="7%" data-orderable="false">Act. Econ</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($bienes))
                    @foreach($bienes as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                            <td width="8%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienes))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoB }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoB }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoB13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoB }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalB }}</td>
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
@endif

@if(isset($bienesCapital) && count($bienesCapital) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Bienes Capital</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports1" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>  
                    <th width="7%" data-orderable="false">Otros Cargos</th>       
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesCapital))
                    @foreach($bienesCapital as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                            <td width="8%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesCapital))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoBC }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoBC }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoBC13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoBC }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalBC }}</td>
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
@endif

@if(isset($servicios) && count($servicios) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Servicios</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports2" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>                  
                    <th width="7%" data-orderable="false">Otros Cargos</th>   
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($servicios))
                    @foreach($servicios as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                             <td width="8%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($servicios))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoS }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoS }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoS13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoS }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalS }}</td>
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
@endif

@if(isset($exento) && count($exento) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Exento</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports3" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>                  
                    <th width="7%" data-orderable="false">Otros Cargos</th>   
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($exento))
                    @foreach($exento as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                             <td width="7%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($exento))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoE }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoE }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoE13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoE }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalE }}</td>
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
@endif

@if(isset($noSujeto) && count($noSujeto) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - No sujeto</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports3" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>                 
                    <th width="7%" data-orderable="false">Otros Cargos</th> 
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th>
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($noSujeto))
                    @foreach($noSujeto as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                             <td width="7%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($noSujeto))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoNS }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoNS }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoNS13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoNS }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalNS }}</td>
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
@endif
@if(isset($fueraAE) && count($fueraAE) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Fuera de la Actividad Economica</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports3" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>                   
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($fueraAE))
                    @foreach($fueraAE as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                             <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                           
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                             <td width="7%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($fueraAE))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoFAE }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoFAE }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoFAE13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoFAE }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalFAE }}</td>
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
@endif
@if(isset($sinClasificar) && count($sinClasificar) > 0)
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Ingresos - Sin Clasificar</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports3" class="table-striped " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Receptor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>  
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($sinClasificar))
                    @foreach($sinClasificar as $index=>$line)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td width="15%" >{{ $line["fecha"] }}</td>
                            <td width="15%" >{{ $line["clave"] }}</td>
                            <td width="15%" >{{ $line["receptor"] }}</td>
                            <td width="7%" class="text-center">{{ $line["monto"] }}</td>
                            <td width="7%" class="text-center">{{ $tax0 = (isset($line["tax0"]))?$line["tax0"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $line["discount"] }}</td>
                            <td width="7%" class="text-center">{{ $tax1 = (isset($line["tax1"]))?$line["tax1"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax2 = (isset($line["tax2"]))?$line["tax2"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax4 = (isset($line["tax4"]))?$line["tax4"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax8 = (isset($line["tax8"]))?$line["tax8"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $tax13 = (isset($line["tax13"]))?$line["tax13"]:0 }}</td>
                     <td width="7%" class="text-center">{{ $exo = (isset($line["exo"]))?$line["exo"]:0 }}</td>
                     <td width="7%" class="text-center">0</td>
                            <td width="7%" class="text-center">{{ $line["total"] }}</td>
                            <td width="7%" class="text-center">{{ $line["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $line["ae"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($sinClasificar))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td width="7%" class="text-center">{{ $sumaMontoSC }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC0 }}</td>
                      <td width="7%" class="text-center">{{ $sumaDescuentoSC }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC1 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC2 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC4 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC8 }}</td>
                      <td width="7%" class="text-center">{{ $sumaImpuestoSC13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoSC }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="7%" class="text-center">{{ $sumaTotalSC }}</td>
                       <td></td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
@endif
@if(isset($bienesG) && count($bienesG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Bienes</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th> 
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesG))
                    @foreach($bienesG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                             <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                             <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoBG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoBG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoBG }}</td>
                      <td width="7%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalBG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($bienesCapitalG) && count($bienesCapitalG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Bienes de Capital</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>    
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th>
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($bienesCapitalG))
                    @foreach($bienesCapitalG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                             <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($bienesCapitalG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoBCG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoBCG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoBCG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoBCG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalBCG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($serviciosG) && count($serviciosG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Servicios</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravedo</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>      
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th>
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($serviciosG))
                    @foreach($serviciosG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($serviciosG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoSG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoSG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoSG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalSG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($exentoG) && count($exentoG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Exento</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                     <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>      
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th>
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($exentoG))
                    @foreach($exentoG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($exentoG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoEG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoEG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoEG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoEG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalEG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($noSujetoG) && count($noSujetoG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - No Sujeto</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>    
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($noSujetoG))
                    @foreach($noSujetoG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($noSujetoG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoNSG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoNSG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoNSG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoNSG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalNSG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($fueraAEG) && count($fueraAEG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Fuera de la Actividad Economica</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false">N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>    
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($fueraAEG))
                    @foreach($fueraAEG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                             <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                           
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($fueraAEG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoFAEG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoFAEG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoFAEG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoFAEG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalFAEG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
@if(isset($sinClasificarG) && count($sinClasificarG) > 0)
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Gastos - Sin Clasifica</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="1%"data-orderable="false" >N°</th>
                    <th width="7%"data-orderable="false">Fecha</th>
                    <th width="15%" data-orderable="false">Numero Factura</th>
                    <th width="15%" data-orderable="false">Emisor</th>
                    <th width="7%" data-orderable="false">Monto Gravado</th>
                    <th width="7%" data-orderable="false">Sin Impuesto</th>
                    <th width="7%" data-orderable="false">Descuento</th>
                    <th width="7%" data-orderable="false">Impuesto 1%</th>
                    <th width="7%" data-orderable="false">Impuesto 2%</th>
                    <th width="7%" data-orderable="false">Impuesto 4%</th>
                    <th width="7%" data-orderable="false">Impuesto 8%</th>
                    <th width="7%" data-orderable="false">Impuesto 13%</th>
                    <th width="7%" data-orderable="false">Exoneracion</th>     
                    <th width="7%" data-orderable="false">Otros Cargos</th>
                    <th width="7%" data-orderable="false">Total</th> 
                    <th width="7%" data-orderable="false">Moneda</th> 
                    <th width="7%" data-orderable="false">Act. Econ</th> 
                </tr>
            </thead>
            <tbody>
                @if(isset($sinClasificarG))
                    @foreach($sinClasificarG as $index2=>$lineG)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index2+1 }}</td>
                            <td width="15%" >{{ $lineG["fechaG"] }}</td>
                            <td width="15%" >{{ $lineG["claveG"] }}</td>
                            <td width="15%" >{{ $lineG["emisor"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["montoG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG0 = (isset($lineG["taxG0"]))?$lineG["taxG0"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $lineG["discountG"] }}</td>
                            <td width="5%" class="text-center">{{ $taxG1 = (isset($lineG["taxG1"]))?$lineG["taxG1"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG2 = (isset($lineG["taxG2"]))?$lineG["taxG2"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG4 = (isset($lineG["taxG4"]))?$lineG["taxG4"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG8 = (isset($lineG["taxG8"]))?$lineG["taxG8"]:0 }}</td>
                            <td width="5%" class="text-center">{{ $taxG13 = (isset($lineG["taxG13"]))?$lineG["taxG13"]:0 }}</td>
                            <td width="7%" class="text-center">{{ $exo = (isset($lineG["exo"]))?$lineG["exo"]:0 }}</td>
                            <td width="8%" class="text-center">{{ $lineG["otrosG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["totalG"] }}</td>
                            <td width="8%" class="text-center">{{ $lineG["tmoneda"] }}</td>
                            <td width="7%" class="text-center">{{ $lineG["aeG"] }}</td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
             <tfoot>
                 @if(isset($sinClasificarG))
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Totales</td>
                      <td>{{ $sumaMontoSCG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG0 }}</td>
                      <td width="5%" class="text-center">{{ $sumaDescuentoSCG }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG1 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG2 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG4 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG8 }}</td>
                      <td width="5%" class="text-center">{{ $sumaImpuestoSCG13 }}</td>
                      <td width="7%" class="text-center">{{ $sumaExoSCG }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $sumaTotalSCG }}</td>
                    </tr>
                @endif
              </tfoot>
        </table>

    </div>
    <!-- end panel-body -->
</div>
<!-- end panel -->
<br>
@endif
<!-- begin panel -->
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
       <h4 class="panel-title">Resumen por categoria</h4>
        
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-reports4" class="table-striped  " width="100%">
            <thead>
                <tr>
                    <th width="7%"data-orderable="false" class="text-center">Clasificación</th>
                    <th width="7%" data-orderable="false" class="text-center">Monto</th>
                    <th width="7%" data-orderable="false" class="text-center">Sin Impuesto</th>
                    <th width="7%" data-orderable="false" class="text-center">Impuesto 1%</th>
                    <th width="7%" data-orderable="false" class="text-center">Impuesto 2%</th>
                    <th width="7%" data-orderable="false" class="text-center">Impuesto 4%</th>
                    <th width="7%" data-orderable="false" class="text-center">Impuesto 8%</th>
                    <th width="7%" data-orderable="false" class="text-center">Impuesto 13%</th>
                    <th width="7%" data-orderable="false" class="text-center">Otros</th> 
                    <th width="7%" data-orderable="false" class="text-center">Total</th> 
                </tr>
            </thead>
            <tbody>
                    <tr class="gradeU">
                    <td width="15%" >Gastos de bienes</td>
                    <td class="text-center">{{ $result=(isset($sumaMontoBG))?$sumaMontoBG:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG0))?$sumaMontoBG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG1))?$sumaMontoBG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG2))?$sumaMontoBG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG4))?$sumaMontoBG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG8))?$sumaMontoBG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBG13))?$sumaMontoBG13:0 }}</td>
                    <td width="8%" class="text-center">0</td>
                    <td width="8%" class="text-center">{{ $result=(isset($sumaTotalBG))?$sumaTotalBG:0 }}</td>
                    </tr>
                    <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos por bienes</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG0))?$sumaImpuestoBG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG1))?$sumaImpuestoBG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG2))?$sumaImpuestoBG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG4))?$sumaImpuestoBG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG8))?$sumaImpuestoBG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBG13))?$sumaImpuestoBG13:0 }}</td>
                    
                    <td width="8%" class="text-center"></td>
                    </tr>
                        <tr class="gradeU">
                            <td width="15%" >Gastos de Bienes de capital</td>
                            <td class="text-center">{{ $result=(isset($sumaMontoBCG))?$sumaMontoBCG:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG0))?$sumaMontoBCG0:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG1))?$sumaMontoBCG1:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG2))?$sumaMontoBCG2:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG4))?$sumaMontoBCG4:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG8))?$sumaMontoBCG8:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoBCG13))?$sumaMontoBCG13:0 }}</td>
                      <td width="8%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $result=(isset($sumaTotalBCG))?$sumaTotalBCG:0 }}</td>
                        </tr>
                        <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos por bienes de capital</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG0))?$sumaImpuestoBCG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG1))?$sumaImpuestoBCG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG2))?$sumaImpuestoBCG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG4))?$sumaImpuestoBCG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG8))?$sumaImpuestoBCG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoBCG13))?$sumaImpuestoBCG13:0 }}</td>
                    <td width="8%" class="text-center"></td>
                    </tr>
                        <tr class="gradeU">
                            <td width="15%" >Gastos de Servicios</td>
                            <td class="text-center">{{ $result=(isset($sumaMontoSG))?$sumaMontoSG:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG0))?$sumaMontoSG0:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG1))?$sumaMontoSG1:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG2))?$sumaMontoSG2:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG4))?$sumaMontoSG4:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG8))?$sumaMontoSG8:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoSG13))?$sumaMontoSG13:0 }}</td>
                       <td width="5%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $result=(isset($sumaTotalSG))?$sumaTotalSG:0 }}</td>
                        </tr>
                          <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos por servicios</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG0))?$sumaImpuestoSG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG1))?$sumaImpuestoSG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG2))?$sumaImpuestoSG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG4))?$sumaImpuestoSG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG8))?$sumaImpuestoSG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoSG13))?$sumaImpuestoSG13:0 }}</td>
                    <td width="8%" class="text-center"></td>
                    </tr>
                        <tr class="gradeU">
                            <td width="15%" >Gastos Exentos</td>
                           <td class="text-center">{{ $result=(isset($sumaMontoEG))?$sumaMontoEG:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG0))?$sumaMontoEG0:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG1))?$sumaMontoEG1:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG2))?$sumaMontoEG2:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG4))?$sumaMontoEG4:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG8))?$sumaMontoEG8:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoEG13))?$sumaMontoEG13:0 }}</td>
                       <td width="5%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $result=(isset($sumaTotalEG))?$sumaTotalEG:0 }}</td>
                        </tr>
                        <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos de gastos exentos</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG0))?$sumaImpuestoEG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG1))?$sumaImpuestoEG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG2))?$sumaImpuestoEG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG4))?$sumaImpuestoEG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG8))?$sumaImpuestoEG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoEG13))?$sumaImpuestoEG13:0 }}</td>
                    <td width="8%" class="text-center"></td>
                    </tr>
                        <tr class="gradeU">
                            <td width="15%" >Gastos no sujetos</td>
                            <td class="text-center">{{  $result=(isset($sumaMontoNSG))?$sumaMontoNSG:0 }}</td>
                      <td width="5%" class="text-center">{{  $result=(isset($sumaMontoNSG0))?$sumaMontoNSG0:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoNSG1))?$sumaMontoNSG1:0 }}</td>
                      <td width="5%" class="text-center">{{  $result=(isset($sumaMontoNSG2))?$sumaMontoNSG2:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoNSG4))?$sumaMontoNSG4:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoNSG8))?$sumaMontoNSG8:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoNSG13))?$sumaMontoNSG13:0 }}</td>
                       <td width="5%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $result=(isset($sumaTotalNSG))?$sumaTotalNSG:0 }}</td>
                        </tr>
                         <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos de gastos no sujetos</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG0))?$sumaImpuestoNSG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG1))?$sumaImpuestoNSG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG2))?$sumaImpuestoNSG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG4))?$sumaImpuestoNSG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG8))?$sumaImpuestoNSG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoNSG13))?$sumaImpuestoNSG13:0 }}</td>
                    <td width="8%" class="text-center"></td>
                    </tr>
                        <tr class="gradeU">
                            <td width="15%" >Gastos fuera actividad econ.</td>
                             <td class="text-center">{{ $result=(isset($sumaMontoFAEG))?$sumaMontoFAEG:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG0))?$sumaMontoFAEG0:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG1))?$sumaMontoFAEG1:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG2))?$sumaMontoFAEG2:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG4))?$sumaMontoFAEG4:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG8))?$sumaMontoFAEG8:0 }}</td>
                      <td width="5%" class="text-center">{{ $result=(isset($sumaMontoFAEG13))?$sumaMontoFAEG13:0 }}</td>
                       <td width="5%" class="text-center">0</td>
                      <td width="8%" class="text-center">{{ $result=(isset($sumaTotalFAEG))?$sumaTotalFAEG:0 }}</td>
                        </tr>
                         <tr class="gradeU text-blue">
                    <td width="15%" class="text-center">Impuestos de gastos fuera de la actividad economica</td>
                    <td class="text-center"></td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG0))?$sumaImpuestoFAEG0:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG1))?$sumaImpuestoFAEG1:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG2))?$sumaImpuestoFAEG2:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG4))?$sumaImpuestoFAEG4:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG8))?$sumaImpuestoFAEG8:0 }}</td>
                    <td width="5%" class="text-center">{{ $result=(isset($sumaImpuestoFAEG13))?$sumaImpuestoFAEG13:0 }}</td>
                    <td width="8%" class="text-center"></td>
                    </tr>
                
            </tbody>
             <tfoot>
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

