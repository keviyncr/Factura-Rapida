@php
$sidebarClass = (!empty($sidebarTransparent)) ? 'sidebar-transparent' : '';
@endphp
<!-- begin #sidebar -->
<div id="sidebar" class="sidebar {{ $sidebarClass }}">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <a href="javascript:;" data-toggle="nav-profile">
                    <div class="cover with-shadow"></div>
                    <div class="">
                        @if(session('company')!=null)
                        @if(session('company')->logo_url == null)
                        <img width="100%" src="{{ asset('admin/img/logo/nologo.png') }}" alt=""  /> 
                        @else
                        <img width="100%" src="{{ asset('laravel/storage/app/public/'.session('company')->logo_url ) }}" alt=""  /> 
                        @endif
                        @else
                        <img width="100%" src="{{ asset('admin/img/logo/nologo.png') }}" alt=""  />  
                        @endif
                        
                    </div>
                    <div class="info">
                        <b class="caret pull-right"></b>
                        @if(session('company')!=null)
                        {{ session('company')->name_company }}
                        @endif
                        <small>Click para más opciones</small>
                    </div>
                </a>
            </li>
            <li>
                <ul class="nav nav-profile">
                    @if( Auth::user()->roll != "Vendedor" )
                    @if(session('company')!=null)
                    @if( Auth::user()->roll != "SuperGlovers" )
                    <li><a href="{{ route('configuration') }}"><i class="fa fa-cog"></i> Configuracion</a></li>
                    @endif
                    @if(count(session('companies') )>1)
                    <li><a href="#company-Modal" data-toggle="modal"><i class="fa fa-pencil-alt"></i> Cambio de compañia</a></li>
                    @endif
                    @endif
                    @if( Auth::user()->roll == "SuperUser" || Auth::user()->roll == "SuperGlovers" )
                    <li><a href="{{ route('adminCompanies') }}"><i class="fa fa-cogs"></i>Administrar Compañias</a></li>
                    
                    @endif
                     @if( Auth::user()->roll == "SuperUser")
                    <li><a href="{{ url('users') }}"><i class="fa fa-users"></i>Administrar Usuarios</a></li>
                    <li><a href="{{ url('billing') }}"><i class="fa fa-users"></i>Facturación</a></li>
                    @endif
                    @endif
                    <li><a href="#modal-term" data-toggle="modal"><i class="fa fa-question-circle"></i> Terminos y condiciones</a></li>
                   
                </ul>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="">
                <a href="/home">
                    <i class="fa fa-th-large"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="has-sub {{ $act=( Auth::user()->roll != 'Vendedor' )?'':'active' }}">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-credit-card"></i>
                    <span>Ventas </span> 
                </a>
                <ul class="sub-menu">
                   
                    <li><a href="{{ url('documents') }}">Todas las ventas</a></li>
                    @if( Auth::user()->roll != "SuperGlovers" )
                    <li><a href="{{ url('clients') }}">Clientes</a></li>
                    <li><a href="{{ url('products') }}">Productos</a></li>
                    @endif
                </ul>
            </li>
           @if( Auth::user()->roll != "Vendedor" )
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Gastos </span> 
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('expenses') }}">Todos los gastos</a></li>
                    @if( Auth::user()->roll != "SuperGlovers" )
                    <li><a href="{{ url('vouchers') }}">Comprobacion</a></li>
                    <li><a href="{{ url('providers') }}">Proveedores</a></li>
                    @endif
                </ul>
            </li>
           
            
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-book"></i>
                    <span>Informes </span> 
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('profitAndLost') }}">Gastos e Ingresos</a></li>
                    <li><a href="{{ url('ivaReport') }}">Reporte IVA</a></li>
                    <li><a href="{{ url('dSales') }}">Detalle de Ventas</a></li>
                    <li><a href="{{ url('dExpenses') }}">Detalle de Gastos</a></li>
                </ul>
            </li>
            @if( Auth::user()->roll != "SuperGlovers" )
            <li>
                <a href="{{ url('discounts') }}">
                    <i class="fa fa-minus-circle"></i>
                    <span>Descuentos</span>
                </a>
            </li> 
            <li>
                <a href="{{ url('taxes') }}">
                    <i class="fa fa-plus-circle"></i>
                    <span>Impuestos</span>
                </a>
            </li> 
            <li>
                <a href="{{ url('exonerations') }}">
                    <i class="fa fa-times-circle"></i>
                    <span>Exoneraciones</span>
                </a>
            </li>
            @endif
            @if( Auth::user()->roll == "SuperUser" )
              <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-upload"></i>
                    <span>Importacion </span> 
                </a>
                <ul class="sub-menu">
                     <li><a href="{{ url('importDocView') }}">Facturas de Glovers</a></li>
                </ul>
            </li>
             @endif
            @endif
            <!-- begin sidebar minify button -->
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->

