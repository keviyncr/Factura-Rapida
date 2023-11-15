
<!-- begin #header -->
<div id="header" class="header navbar-default">
    <!-- begin navbar-header -->
    <div class="navbar-header">
        <a href="{{ url('/home') }}" class="navbar-brand"><span class=""><img src="{{ asset('frontend/img/logoFR-2.png') }}" width="150" alt="Factura Rapida"></span> </a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- end navbar-header -->


    <!-- begin header-nav -->
    <ul class="navbar-nav navbar-right navbar-default">

        <li class="dropdown navbar-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                
                @if(session('company')!=null)
                
                @if(session('company')->logo_url == null)
                <img src="{{ asset('admin/img/logo/nouser.jpg') }}" alt=""  /> 
                @else
                <img src="{{ asset('laravel/storage/app/public/'.session('company')->logo_url ) }}" alt=""  /> 
                @endif
                @else
                <img src="{{ asset('admin/img/logo/nouser.jpg') }}" alt=""  /> 
                @endif
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> <b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                    {{ __('Salir') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
    <!-- end header navigation right -->
</div>
<!-- end #header -->
