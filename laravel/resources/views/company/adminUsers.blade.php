@extends('layouts.default')
@section('title', 'Usuarios')

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

<link href="{{ asset('admin/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('admin/plugins/abpetkov-powerange/dist/powerange.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-plugins-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Listado de Usuarios</h4>
        <div class="panel-heading-btn">
           <a href="#modal-addUser"  data-toggle="modal" title="Nuevo usuario" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>

        </div>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body ">
        <table id="data-table-products" class="table table-striped table-bordered table-td-valign-middle">
            <thead>
                <tr>
                    <th width="1%">ID</th>
                    <th width="10%" class="text-nowrap">Estado</th>
                    <th width="10%" class="text-nowrap">Editar</th>
                    <th width="25%" class="text-nowrap">Nombre</th>
                    <th width="25%" class="text-nowrap">Usuario</th>
                    <th width="25%" class="text-nowrap">Roll</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index=>$user)
                <tr class="gradeU">
                    <td width="1%" >{{ $index+1 }}</td>
                    <td width="5%">
                        <div class="switcher">
                              @if($user->active)
                              <input type="checkbox" name="switcher_checkbox_1" id="{{ $user->id }}" onchange="doalert(this.id)"  checked="true" value="1">
                              <label for="{{ $user->id }}"></label>
                              @else
                              <input type="checkbox" name="switcher_checkbox_1" id="{{ $user->id }}" onchange="doalert(this.id)" value="0">
                              <label for="{{ $user->id }}"></label>
                              @endif
                        </div>
                    </td>
                     <td width="5%">
                        <div class="btn-group btn-group-justified">
                                <button type="button" title="Editar" onclick="chargeEditModalUser({{ $user->id }});" class="btn btn-default" ><i class="fa fa-edit"></i></button>
                              
                            </div>
                    </td>
                    <td width="25%">{{ $user->name }}</td>
                    <td width="25%">{{ $user->email }}</td>
                    <td width="25%">
                     <select onchange='changeRollUser(this.value,"{{ $user->id }}")' class="form-control" data-parsley-required="true" name="roles" id="roles" >
                        <option style="color: black;" value="SuperUser" {{ $c=($user->roll == 'SuperUser')?'selected':'' }} >SuperUser</option>
                        <option style="color: black;" value="SuperGlovers" {{ $c=($user->roll == "SuperGlovers")?"selected":"" }}> SuperGlovers</option>
                        <option style="color: black;" value="Administrador" {{ $c=($user->roll == "Administrador")?"selected":"" }} >Administrador</option>
                        <option style="color: black;" value="Contador" {{ $c=($user->roll == "Contador")?"selected":"" }} >Contador</option>
                       
                    </select>
                    </td>
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
<div class="modal fade" id="modal-addUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nuevo usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/users') }}" method="POST" name="add_user" id="add_user" class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Usuario: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name" data-parsley-required="true" class="form-control" placeholder="Nombre Usuario" />
                        </div>                       
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="email" class="form-control"  data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico"/>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Contraseña: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="password" name="password" class="form-control"  data-parsley-required="true" data-parsley-type="password" placeholder="Contraseña"/>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Roll: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="roll" id="roll" >
                                <option style="color: black;" value="SuperUser" {{ $c=($user->roll == 'SuperUser')?'selected':'' }} >SuperUser</option>
                                <option style="color: black;" value="SuperGlovers" {{ $c=($user->roll == "SuperGlovers")?"selected":"" }}> SuperGlovers</option>
                                <option style="color: black;" value="Administrador" {{ $c=($user->roll == "Administrador")?"selected":"" }} >Administrador</option>
                            </select>
                        </div>
                    </div>
                    <!-- end form-group -->
                   
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_user" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addClient -->
<!-- #modal-updateClient -->
<div class="modal fade" id="modal-updateUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar datos de Usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/users') }}" method="POST" name="update_user" id="update_user" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Usuario: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name" id="name" data-parsley-required="true" class="form-control" placeholder="Nombre Usuario" />
                        </div>                       
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="email" id="email" class="form-control"  data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico"/>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Roll: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="roll" id="roll_u" >
                                <option style="color: black;" value="SuperUser" {{ $c=($user->roll == 'SuperUser')?'selected':'' }} >SuperUser</option>
                                <option style="color: black;" value="SuperGlovers" {{ $c=($user->roll == "SuperGlovers")?"selected":"" }}> SuperGlovers</option>
                                <option style="color: black;" value="Administrador" {{ $c=($user->roll == "Administrador")?"selected":"" }} >Administrador</option>
                            </select>
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_user" class="btn btn-success">Actualizar</button>

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
<script src="{{ asset('admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ asset('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
<script src="{{ asset('admin/js/user.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="{{ asset('admin/plugins/switchery/switchery.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/abpetkov-powerange/dist/powerange.min.js') }}"></script>
	<script src="{{ asset('admin/js/demo/form-slider-switcher.demo.js') }}"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

@endpush

