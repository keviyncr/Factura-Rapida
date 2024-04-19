@extends('layouts.default')
@section('title', 'Productos')

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
        <h4 class="panel-title">Listado de productos</h4>
        <div class="panel-heading-btn">
            <a href="#modal-addProduct"  data-toggle="modal" title="Nuevo Producto" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-client" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="7%"data-orderable="false">Acciones</th>
                    <th width="10%" class="text-nowrap">Codigo</th>
                    <th width="10%" class="text-nowrap">Descricion</th>
                    <th width="4%" class="text-nowrap">U.medida</th>
                    <th width="7%" class="text-nowrap">P. Unid.</th>
                    <th width="7%" class="text-nowrap">Descuento</th>
                    <th width="7%" class="text-nowrap">Impueto</th>                    
                    <th width="7%" class="text-nowrap">Exonerado</th>                    
                    <th width="7%" class="text-nowrap">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="gradeU">
                    <td width="1%" >{{ $product->id }}</td>
                    <td>
                        <form method="post" action="{{ url ('products/'.$product->id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="btn-group btn-group-justified">
                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModalProduct({{ $product -> id }});"><i class="fa fa-edit"></i></button>
                                <button type="submit" title="Borrar" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                            </div>
                        </form> 
                    </td>
                    <td>{{ str_pad($product->cabys, 13, "0", STR_PAD_LEFT)  }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->sku_symbol }}</td>                    
                    <td>{{ $product->price_unid }}</td>
                    <td>{{ $product->total_discount }}</td>
                    <td>{{ $product->total_tax }}</td>
                    <td>{{ $product->total_exoneration }}</td>
                    <td>{{ $product->total_price }}</td>
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
<!-- #modal-addProduct -->
<div class="modal fade" id="modal-addProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nuevo producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/products') }}" method="POST" name="add_product" id="add_product" class="form-horizontal form-bordered">
                    @csrf
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tipo de codigo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control"  name="type_internal_code" id="type_internal_code" id="select-required">
                                <option style="color: black;" value="00">Seleccionar...</option>
                                <option style="color: black;" value="01">Código del producto del vendedor</option>
                                <option style="color: black;" value="02">Código del producto del comprador</option>
                                <option style="color: black;" value="03">Código del producto asignado por la industria</option>
                                <option style="color: black;" value="04">Código uso interno </option>
                                <option style="color: black;" value="99">Otros</option>
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Codigo Interno: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                             <input type="text" name="internal_code" id="internal_code" class="form-control" placeholder="Codigo Interno" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Cabys: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                             <input type="text" name="cabys" id="cabys" data-parsley-required="true" class="form-control" placeholder="Codigo Cabys" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
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
                        <label class="col-lg-3 col-form-label">Partida Arancelaria: <br>(encaso de mercancia de exportación): <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="tariff_heading" id="tariff_heading" value="0" class="form-control" placeholder="Partida Arancelaria (encaso de mercancia de exportación)"  />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Unidad medida: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control" data-parsley-required="true" name="id_sku" id="id_sku" id="select-required">
                                @foreach($skuses as $sku)                                       
                                <option style="color: black;" value="{{ $sku->id }}">{{ $sku->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Precio Unitario: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input  data-toggle="number" step="any" data-placement="after" class="form-control" type="number"  name="price_unid" id="price_unid" placeholder="Precio unidad" data-parsley-required="true" value="0" onchange="calculator()"/>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row"  id="group_tax_base">
                        <label class="col-lg-3 col-form-label">Base imponible: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input onchange="calculator()" step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="tax_base" id="tax_base" placeholder="Base Imponible" value="0" />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descuentos:
                            (click para añadir)<span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select onchange="calculator()" class="multiple-select2 form-control" name="ids_discounts[]" id="ids_discounts" multiple="multiple" placeholder="Impuestos" on ="calculator()">
                                @foreach($discounts as $discount)                                       
                                <option style="color: black;" value="{{ $discount->id }}">{{ $discount->nature }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Descuento total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_discount" id="total_discount" placeholder="Descuento total" data-parsley-required="true" value="0" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Impuestos:
                            (click para añadir)<span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select onchange="calculator()" class="multiple-select2 form-control" multiple="multiple" name="ids_taxes[]" id="ids_taxes" placeholder="Impuestos" >
                                @foreach($taxes as $tax)                                       
                                <option value="{{ $tax->id }}" >{{ $tax->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Impuesto total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_tax" id="total_tax" placeholder="Impuesto total" data-parsley-required="true" value="0" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Exoneracion total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_exoneration" id="total_exoneration" placeholder="Exoneracion total" data-parsley-required="true" value="0" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number" value="0" name="total_price" id="total_price" placeholder="Precio total" data-parsley-required="true"  />
                        </div>
                    </div>
                    <!-- begin form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_product" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addProduct -->
<!-- #modal-updateProduct -->
<div class="modal fade" id="modal-updateProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/products') }}" method="POST" name="update_product" id="update_product" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                       <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tipo de codigo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control"  name="type_internal_code" id="type_internal_codeU">
                                <option style="color: black;" value="00">Seleccionar...</option>
                                <option style="color: black;" value="01">Código del producto del vendedor</option>
                                <option style="color: black;" value="02">Código del producto del comprador</option>
                                <option style="color: black;" value="03">Código del producto asignado por la industria</option>
                                <option style="color: black;" value="04">Código uso interno </option>
                                <option style="color: black;" value="99">Otros</option>
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Codigo Interno: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                             <input type="text" name="internal_code" id="internal_codeU" class="form-control" placeholder="Codigo Interno" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Código de cabys: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                           <input type="text" name="cabys" id="cabysU" data-parsley-required="true" class="form-control" placeholder="Codigo Interno" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
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
                        <label class="col-lg-3 col-form-label">Partida Arancelaria: <br> (encaso de mercancia de exportación) <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="tariff_heading" id="tariff_headingU" value="0" class="form-control" placeholder="Partida Arancelaria (encaso de mercancia de exportación)" />
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Unidad medida: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control" data-parsley-required="true" name="id_sku" id="id_skuU" id="select-required">
                                @foreach($skuses as $sku)                                       
                                <option style="color: black;" value="{{ $sku->id }}">{{ $sku->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Precio Unitario: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input  data-toggle="number" step="any" data-placement="after" class="form-control" type="number"  name="price_unid" id="price_unidU" placeholder="Precio unidad" data-parsley-required="true" onchange="calculatorU()" value="0"/>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row"  id="group_tax_base">
                        <label class="col-lg-3 col-form-label">Base imponible: <span class="text-danger"></span></label>
                        <div class="col-lg-9">                            
                            <input onchange="calculatorU()" step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="tax_base" id="tax_baseU" placeholder="Base Imponible" value="0" />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descuentos:
                            (click para añadir)<span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select onchange="calculatorU()" class="multiple-select2 form-control" name="ids_discounts[]" id="ids_discountsU" multiple="multiple" placeholder="Impuestos" on ="calculator()">
                                <option style="color: black;" value="">Ninguno</option>
                                @foreach($discounts as $discount)                                       
                                <option style="color: black;" value="{{ $discount->id }}">{{ $discount->nature }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Descuento total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_discount" id="total_discountU" placeholder="Descuento total" data-parsley-required="true" value="0"  />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Impuestos:
                            (click para añadir)<span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select onchange="calculatorU()" class="multiple-select2 form-control" multiple="multiple" name="ids_taxes[]" id="ids_taxesU" placeholder="Impuestos" >
                                @foreach($taxes as $tax)                                       
                                <option value="{{ $tax->id }}" >{{ $tax->description }}</option>
                                @endforeach
                            </select>
                        </div>                       
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label" >Impuesto total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_tax" id="total_taxU" placeholder="Impuesto total" data-parsley-required="true" value="0" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Exoneracion total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" data-placement="after" class="form-control" type="number"  name="total_exoneration" id="total_exonerationU" placeholder="Exoneracion total" data-parsley-required="true" value="0" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <!-- end form-group -->
                    <div class="form-group row" >
                        <label class="col-lg-3 col-form-label">Total: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input step="any" data-toggle="number" value="0" data-placement="after" class="form-control" type="number"  name="total_price" id="total_priceU" placeholder="Precio total" data-parsley-required="true"  />
                        </div>
                    </div>
                    <!-- begin form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_product" class="btn btn-success">Actualizar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-updateProduct -->
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
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/js/product.js') }}"></script>
@endpush

