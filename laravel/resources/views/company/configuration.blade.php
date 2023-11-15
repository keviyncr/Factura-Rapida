@extends('layouts.app')
@section('title', 'Configuracion')

@push('css')
<link href="{{ asset('admin/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />




<link href="{{ asset('admin/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />


<link href="{{ asset('admin/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-autofill-bs4/css/autofill.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-colreorder-bs4/css/colreorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-keytable-bs4/css/keytable.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" />




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

<link href="{{ asset('admin/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- begin profile -->
<div class="profile">
    <div class="profile-header">
        <!-- BEGIN profile-header-cover -->
        <div class="profile-header-cover"></div>
        <!-- END profile-header-cover -->
        <!-- BEGIN profile-header-content -->
        <div class="profile-header-content">
            <!-- BEGIN profile-header-img -->
            <div class="profile-header-img">
                @if(session('company')!=null)
                @if(session('company')->logo_url !=null) 
                <img width="100%" height="100%" src="{{ asset('laravel/storage/app/public/'.session('company')->logo_url ) }}" alt="">
                @else
                <img width="100%" height="100%" src="{{ asset('admin/img/logo/nologo.png') }}" alt="">
                @endif
                @else
                <img width="100%" height="100%" src="{{ asset('admin/img/logo/nologo.png') }}" alt="">
                @endif


            </div>
            <!-- END profile-header-img -->
            <!-- BEGIN profile-header-info -->
            <div class="profile-header-info">
                <h4 class="mt-0 mb-1">
                    @if(session('company')!=null)
                    {{ session('company')->name_company }}
                    @endif</h4>
                <p class="mb-2">Cambiar logo</p>
                <form action="{{ route('saveImage') }}" method="POST"  class="form-horizontal" enctype="multipart/form-data" name="form-wizard" class="form-control-with-bg">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <div class="col-lg-5 col-xl-5">
                            <input type="file" accept="image/*" name="logo_url" class="form-control" data-parsley-required="true" required/>
                        </div>
                        <div class="col-lg-5 col-xl-5">
                            <button type="submit" class="btn btn-danger btn-md">Guardar</button>
                        </div>
                    </div>

                </form>
            </div>
            <!-- END profile-header-info -->
        </div>
        <!-- END profile-header-content -->
        <!-- BEGIN profile-header-tab -->
        <ul class="profile-header-tab nav nav-tabs">
            <li class="nav-item"><a href="#profile-company" class="nav-link {{ empty(Session::get('tab')) || Session::get('tab') == 'profile-company' ? 'active' : '' }}" data-toggle="tab">COMPAÑIA</a></li>
            <li class="nav-item"><a href="#profile-branchOffice" class="nav-link {{ !empty(Session::get('tab')) && Session::get('tab') == 'profile-branchOffice' ? 'active' : '' }}" data-toggle="tab">SUCURSALES</a></li>
            <li class="nav-item"><a href="#profile-user" class="nav-link {{ !empty(Session::get('tab')) && Session::get('tab') == 'profile-user' ? 'active' : '' }}" data-toggle="tab">USUARIOS</a></li>
        </ul>
        <!-- END profile-header-tab -->
    </div>
</div>
<!-- end profile -->
<!-- begin profile-content -->
<div class="profile-content">
    <!-- begin tab-content -->
    <div class="tab-content p-0">
        <!-- begin #profile-post tab -->
        <div class="tab-pane fade {{ empty(Session::get('tab')) || Session::get('tab') == 'profile-company' ? 'show active' : '' }}" id="profile-company">
            <!-- begin panel -->
            <div class="panel panel-inverse"  data-sortable-id="form-plugins-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Modificar Datos de la Compañia</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:enableConfiguration();" hidden="true" title="Bloquear" id="btn_BlockConfigure" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-lock-open"></i></a>
                        <a href="javascript:enableConfiguration();" title="Desbloquear" id="btn_DesblockConfigure" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-lock"></i></a>
                        <a href="javascript:;" class="btn btn-md btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body panel-form">
                    <form data-parsley-validate="true" action="{{ url('/companies/'. session('company')->id) }}" method="POST" name="update_company" id="update_companyt" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Nombre compañia: <span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <div class="input-group date" >
                                    <input disabled="true" type="text" name="name_company" id="name_company" data-parsley-group="step-1" data-parsley-required="true" class="form-control" placeholder="Nombre de la compañia" value="{{ session('company')->name_company }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Identifiacion: <span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <div class="row row-space-10">
                                    <div class="col-xs-6 mb-2 mb-sm-0">
                                        <input disabled="true" data-toggle="number" data-placement="after" class="form-control" type="number"  name="id_card" id="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12" data-parsley-group="step-1" data-parsley-required="true" value="{{ session('company')->id_card }}" />
                                    </div>
                                    <div class="col-xs-6">
                                        <select disabled="true" class="form-control" data-parsley-required="true" name="type_id_card" id="type_id_card" id="type_id_card">
                                            <option style="color: black;" value="" disabled selected="true">Tipo identifiación</option>
                                            @foreach($type_id_cards as $type_id_card)
                                            @if (session('company')->type_id_card == $type_id_card->id)
                                            <option style="color: black;" selected="true" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                            @else
                                            <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Usuario MH: </label>
                            <div class="col-lg-8">
                                <div class="input-group date" id="datetimepicker2">
                                    <input disabled="true" value="{{ session('company')->user_mh }}" data-toggle="text" data-placement="after" class="form-control" type="text"  name="user_mh" id="user_mh" data-parsley-group="step-1" data-parsley-required="true"  placeholder="Usuario brindado en el ATV"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Contraseña MH: </label>
                            <div class="col-lg-8">
                                <div class="input-group date" id="datetimepicker2">
                                    <input disabled="true" value="{{ session('company')->pass_mh }}" data-toggle="text" data-placement="after" class="form-control" type="text"  name="pass_mh" id="pass_mh" data-parsley-group="step-1" data-parsley-required="true"  placeholder="Contraseña brindada en el ATV"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-lg-10 ">

                            </div>
                            <div class="col-lg-2 ">
                                <div class="input-group " >
                                    <button hidden="true" type="submit" id="btn_updateCompany" class="btn btn-primary btn-lg ">Modificar</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Datos de llave cripyográfica</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-md btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body panel-form">
                    <form data-parsley-validate="true" action="{{ route('updateCryptoKey') }}" method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                        @csrf
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Lave criptográfica: <span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <div class="input-group date" id="datetimepicker2">
                                    <input  value="{{ session('company')->cryptographic_key }}" data-toggle="file" data-placement="after" class="form-control" type="file"  id="cryptographic_key" name="cryptographic_key" placeholder="Llave Criptografica" data-parsley-required="true"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">PIN: <span class="text-danger"></span></label>
                            <div class="col-lg-8">
                                <div class="input-group date" id="datetimepicker2">
                                    <input  value="{{ session('company')->pin }}" data-toggle="password" data-placement="after" class="form-control" type="text"  name="pin" id="pin" placeholder="PIN de llave criptografica" minlength="4" maxlength="4"  data-parsley-required="true"  />
                                </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-lg-10 ">

                            </div>
                            <div class="col-lg-2 ">
                                <div class="input-group " >
                                    <button type="submit" id="btn_updateCryptoKey" class="btn btn-primary btn-lg ">Modificar</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Listado actividades economicas</h4>
                    <div class="panel-heading-btn">
                        <a href="#modal-dialog"  data-toggle="modal" title="Nueva actividad economica" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>
                        <a href="javascript:;" class="btn btn-md btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body ">
                    <table id="data-table-ea" class="table table-striped table-bordered table-td-valign-middle">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="5%" data-orderable="false">Acciones</th>
                                <th  width="30%" class="text-nowrap">Codigo</th>
                                <th  width="60%"  class="text-nowrap">nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($e_as as $i=>$economic_activity)
                            <tr class="gradeU">
                                <td width="1%" >{{ $i+1 }}</td>
                                <td>
                                    <form method="post" action="{{ url ('companieseconomicactivities/'.$economic_activity->id_c_ea)}}">
                                        @csrf
                                        @method('DELETE')
                                        @if(count($e_as)<2)
                                        <button disabled="true" type="submit" title="Debe tener almenos una actividad registrada" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                                        @else
                                        <button type="submit" title="Borrar" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </form>                                        
                                    </div></td>
                                <td>{{ $economic_activity->number }}</td>
                                <td>{{ $economic_activity->name_ea }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end #profile-post tab -->
        <!-- begin #profile-about tab -->
        <div class="tab-pane fade {{ !empty(Session::get('tab')) && Session::get('tab') == 'profile-branchOffice' ? 'show active' : '' }}" id="profile-branchOffice">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                <div class="panel panel-inverse">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">Listado de Sucursales</h4>
                        <div class="panel-heading-btn">
                            <a href="#modal-BranchOffice"  data-toggle="modal" title="Nueva sucursal" class="btn btn-md btn-icon btn-circle btn-danger"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <table id="data-table-sucursales" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                                <tr>
                                    <th width="1%">ID</th>
                                    <th data-orderable="false">Acciones</th>
                                    <th class="text-nowrap">Razon Social</th>
                                    <th class="text-nowrap">Provincia</th>
                                    <th class="text-nowrap">Canton</th>
                                    <th class="text-nowrap">Distrito</th>
                                    <th class="text-nowrap">Telefono</th>
                                    <th class="text-nowrap">Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branch_offices as $branch_office)
                                <tr class="gradeU">
                                    <td width="1%">{{ $branch_office->id }}</td>
                                    <td>

                                        <form method="post" action="{{ url ('branchoffices/'.$branch_office->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            @if(count($branch_offices)<2)
                                            <div class="btn-group btn-group-justified">
                                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModal({{ $branch_office->id }})"><i class="fa fa-edit"></i></button>
                                                <button type="button" title="Cambio de consecutivos" class="btn btn-default" onclick="chargeEditModalConsecutives({{ $branch_office->id }});"><i >Consecutivos</i></button>
                                                <!--<button disabled="true" type="submit" title="Debe de tener almenos una sucursal activa" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>-->
                                            </div>
                                            @else
                                            <div class="btn-group btn-group-justified">
                                                <button type="button" title="Editar" class="btn btn-default" onclick="chargeEditModal({{ $branch_office->id }});"><i class="fa fa-edit"></i></button>
                                                <button type="button" title="Cambio de consecutivos" class="btn btn-default" onclick="chargeEditModalConsecutives({{ $branch_office->id }});"><i >Consecutivos</i></button>
                                                <!--<button type="submit" title="Activo" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>-->
                                            </div>
                                            @endif
                                        </form>  

                                    </td>                                    
                                    <td>{{ $branch_office->name_branch_office }}</td>
                                    <td>{{ $branch_office->province}}</td>
                                    <td>{{ $branch_office->canton}}</td>
                                    <td>{{ $branch_office->district }}</td>
                                    <td>{{ $branch_office->phone }}</td>
                                    <td>{{ $branch_office->emails }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- end panel-body -->
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end #profile-about tab -->
        <!-- begin #profile-videos tab -->
        <div class="tab-pane fade {{ !empty(Session::get('tab')) && Session::get('tab') == 'profile-user' ? 'show active' : '' }}" id="profile-user">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                <div class="panel panel-inverse">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <h4 class="panel-title">Listado de Usuarios</h4>
                        <div class="panel-heading-btn">
                         
                            <a href="#modal-addUser"  data-toggle="modal" title="Nuevo usuario" class="btn btn-md  btn-danger"><i >Agregar usuario</i></a>
                            
                        </div>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <table id="data-table-users" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                                <tr>
                                    <th width="1%">ID</th>
                                    <th data-orderable="false">Acciones</th>
                                    <th class="text-nowrap">Nombre</th>
                                    <th class="text-nowrap">Correo</th>
                                    <th class="text-nowrap">Roll</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="gradeU">
                                    <td width="1%" >{{ $user->id }}</td>
                                    <td>   
                                        <form method="post" action="{{ url ('users/'.$user->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            @php
                                                $cont = 0;
                                            @endphp
                                                @foreach($users as $u)
                                                    @php
                                                        $cont++;
                                                    @endphp
                                                @endforeach
                                            @if( $cont < 2 &&  $user->roll == "Administrador")
                                                <div class="btn-group">
                                                    <button disabled="true" type="submit" title="Debe de tener almenos un usuario activo" class="btn btn-default"><i class="fa fa-trash"></i></button>
                                                </div>
                                            @else
                                            <div class="btn-group">
                                                <button type="submit" title="Activo" class="btn btn-default" onclick="return confirm('Desea Borrar?');"><i class="fa fa-trash"></i></button>
                                            </div>
                                            @endif
                                        </form> 
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roll}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- end panel-body -->
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end #profile-videos tab -->
    </div>
    <!-- end tab-content -->
</div>
<!-- end profile-content -->
@endsection
@section('content-modal')
<!-- #modal-dialog -->
<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar actividades economicas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/companieseconomicactivities') }}" method="POST" name="add_ae" id="add_ae" class="form-horizontal form-bordered">
                    @csrf

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Actividad económica: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control" name="economic_activities" id="economic_activities" required="true" >
                                <option style="color: black;" value="0"  selected="true">Actividad Economica</option>
                                @foreach($economic_activities as $economic_activity)
                                {{ $b=false }}
                                @foreach($e_as as $e_a)
                                @if($economic_activity->id == $e_a->id)
                                {{ $b=true }}
                                @endif
                                @endforeach
                                @if(!$b)
                                <option style="color: black;" value="{{ $economic_activity->id }}">{{ $economic_activity->number." - ".$economic_activity->name_ea }}</option>
                                @endif                                
                                @endforeach
                            </select>
                        </div>
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_ae" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-without-animation -->
<!-- #modal-dialog -->
<div class="modal fade" id="modal-BranchOffice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar sucursales</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/branchoffices') }}" method="POST" name="add_bo" id="add_bo" class="form-horizontal form-bordered">
                    @csrf

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input type="text" name="name_branch_office" data-parsley-group="step-2" data-parsley-required="true" class="form-control" placeholder="Nombre sucursal" />
                        </div>
                        <div class="col-3">
                            <input data-toggle="number" data-placement="after" class="form-control" type="number"  name="number" value="{{ count($branch_offices)+1 }}" placeholder="Numero"  data-parsley-required="true"  />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_province" id="id_province" data-parsley-group="step-2">                                                               
                                        <option style="color: black;" value="0" selected="true" disabled="true">Provincia</option>
                                        @foreach($provinces as $province)
                                        <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_canton" id="id_canton" data-parsley-group="step-2">
                                        <option style="color: black;" value="0" selected="true" disabled="true">Cantón</option>

                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_district" id="id_district" data-parsley-group="step-2">
                                        <option style="color: black;" value="0" selected="true" disabled="true">Distrito</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row row-space-12">                                                        
                                <div class="col-lg-12 col-xl-12">
                                    <input type="text" name="other_signs" data-parsley-required="true" class="form-control" placeholder="Otras señas"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="emails" class="form-control" data-parsley-group="step-2" data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico para informacion (va en la factura)"/>
                        </div>
                    </div>
                    <!-- end form-group -->

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" >
                                        <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                        @foreach($country_codes as $country_code)
                                        @if( $country_code->phone_code == "506")
                                        <option style="color: black;" selected="true" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number"  name="phone" placeholder="N. Telefonico" minlength="8" maxlength="8" data-parsley-group="step-2" data-parsley-required="true"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="add_bo" class="btn btn-success">Agregar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-without-animation -->
<!-- #modal-dialog -->
<div class="modal fade" id="modal-BranchOfficeUpadate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar sucursal </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="" method="POST" name="update_bo" id="update_bo" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input type="text" name="name_branch_office" id="name_branch_officeU" data-parsley-required="true" class="form-control" placeholder="Nombre sucursal" />
                            <input hidden="true" type="text" name="id" id="idU" class="form-control" placeholder="" />
                        </div>
                        <div class="col-3">
                            <input disabled="true" data-toggle="number" data-placement="after" class="form-control" type="number"  name="number2" id="number2U" value="" placeholder="Numero"  data-parsley-required="true"  />
                            <input hidden ="true" data-toggle="number" data-placement="after" class="form-control" type="number"  name="number" id="numberU" value="" placeholder="Numero"  data-parsley-required="true"  />
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_province" id="id_provinceU" >                                                               
                                        <option style="color: black;" value="0" selected="true" disabled="true">Provincia</option>
                                        @foreach($provinces as $province)
                                        <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_canton" id="id_cantonU" >
                                        <option style="color: black;" value="0" selected="true" disabled="true">Cantón</option>

                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_district" id="id_districtU" >
                                        <option style="color: black;" value="0" selected="true" disabled="true">Distrito</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row row-space-12">                                                        
                                <div class="col-lg-12 col-xl-12">
                                    <input type="text" name="other_signs" id="other_signsU" data-parsley-required="true" class="form-control" placeholder="Otras señas"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="emails" id="emailsU" class="form-control"  data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico para informacion (va en la factura)"/>
                        </div>
                    </div>
                    <!-- end form-group -->

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" id="id_country_codeU" >
                                        <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                        @foreach($country_codes as $country_code)
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number"  id="phoneU" name="phone" placeholder="N. Telefonico" minlength="8" maxlength="8" data-parsley-group="step-2" data-parsley-required="true"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_bo" class="btn btn-success">Actualizar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-without-animation -->

<!-- #modal-dialog -->
<div class="modal fade" id="modal-consecutives">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar consecutivos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form  action="" method="POST" name="update_consecutives" id="update_consecutives" class="form-horizontal form-bordered">
                    @csrf
                    @method('PATCH')
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Facturas Electronicas: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_fe" type="number" name="c_fe" placeholder="{{ __('c_fe') }}" value="{{ old('c_fe') }}" required autocomplete="c_fe" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Notas de credito: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_nc" type="number" name="c_nc" placeholder="{{ __('c_nc') }}" value="{{ old('c_nc') }}" required autocomplete="c_nc" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Notas de debito: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_nd" type="number" name="c_nd" placeholder="{{ __('c_nd') }}" value="{{ old('c_nd') }}" required autocomplete="c_nd" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Facturas de compra: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_fc" type="number" name="c_fc" placeholder="{{ __('c_fc') }}" value="{{ old('c_fc') }}" required autocomplete="c_fc" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Facturas de exportacion: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_fex" type="number" name="c_fex" placeholder="{{ __('c_fex') }}" value="{{ old('c_fex') }}" required autocomplete="c_fex" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tiquetes Electronicos: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_te" type="number" name="c_te" placeholder="{{ __('c_te') }}" value="{{ old('c_te') }}" required autocomplete="c_te" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Documentos Aceptados: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_mra" type="number" name="c_mra" placeholder="{{ __('c_mra') }}" value="{{ old('c_mra') }}" required autocomplete="c_mra" autofocus>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Documentos Rechazados: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input id="c_mrr" type="number" name="c_mrr" id="c_mrr" placeholder="{{ __('c_mrr') }}" value="{{ old('c_mrr') }}" required autocomplete="c_mrr" autofocus>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="update_consecutives" class="btn btn-danger ">
                    {{ __('Guardar') }}
                </button>

            </div>
        </div>
    </div>
</div>

<!-- #modal-addClient -->
<div class="modal fade" id="modal-addUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar nuevo usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form data-parsley-validate="true" action="{{ url('/addAccountant') }}" method="POST" name="add_user" id="add_user" class="form-horizontal form-bordered">
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
                        <label class="col-lg-3 col-form-label">Rol: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="default-select2 form-control" name="roll" id="roll" >
                                <option style="color: black;" value="Contador" >Contador</option>
                                <option style="color: black;" value="Vendedor" >Vendedor</option>
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
<script src="{{ asset('admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script src="{{ asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script src="{{ asset('admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin/js/demo/ui-modal-notification.demo.js') }}"></script>
<script src="{{ asset('admin/js/address.js') }}"></script>
<script src="{{ asset('admin/js/configuration.js') }}"></script>
@endpush