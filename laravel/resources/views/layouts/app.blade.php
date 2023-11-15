<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('includes.head')
    </head>
    <body>
        <!-- begin page-cover -->
        <div class="page-cover"></div>
        <!-- end page-cover -->

        <div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed">

            @include('includes.header')
            @include('includes.sidebar')

            <div id="content" class="content">
                @yield('content')
            </div>
            @include('includes.component.scroll-top-btn')

        </div>
        <div id="content-modal" class="">
            @yield('content-modal')            
        </div>
        @include('includes.footer')
        @include('includes.page-js')
    </body>

</html>
