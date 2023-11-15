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
                <div class="row">
                    <!-- begin col-6 -->
                    <div class="col-xl-8">
                        <!-- begin card -->
                        <div class="card border-0 mb-3 overflow-hidden">
                            <!-- begin card-body -->
                            <div class="card-body">
                                <!-- begin row -->
                                <div class="row">
                                    <!-- begin col-7 -->
                                    <div class="col-xl-6 col-lg-8">
                                        <!-- begin title -->
                                        <div class="mb-3 text-grey">
                                            <b>TOTAL GANANCIAS</b>
                                        </div>
                                        <!-- end title -->
                                        <!-- begin total-sales -->
                                        <div class="d-flex mb-1">
                                            <h2 class="mb-0">CRC <span data-animation="number" data-value="{{ round(session('sales')-session('purchases'),2) }}">0.00</span></h2>
                                            <div class="ml-auto mt-n1 mb-n1"><div id="total-sales-sparkline"></div></div>
                                        </div>
                                        <!-- end total-sales -->
                                        <!-- begin percentage -->
                                        <div class="mb-3 text-grey">
                                            <i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{ $result=(session('purchases')!=0 && session('sales')>0)? round(((session('sales')-session('purchases'))/session('sales')*100),2):100 }}">0.00</span>% obtenido
                                        </div>
                                        <!-- end percentage -->                     

                                    </div>
                                    <!-- end col-7 -->
                                    <!-- begin col-5 -->
                                    <div class="col-xl-6 col-lg-8 align-items-center ">
                                        <!-- begin row -->
                                        <div class="row text-truncate">
                                            <!-- begin col-6 -->
                                            <div class="col-6">
                                                <div class="f-s-12 text-grey">Ventas Totales</div>
                                                <div class="f-s-18 m-b-5 f-w-600 p-b-1">CRC <span data-animation="number" data-value="{{ round(session('sales'),2) }}">0.00</span></div>
                                                <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                                    <div class="progress-bar progress-bar-striped rounded-right bg-teal" data-animation="width" data-value="55%" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <!-- end col-6 -->
                                            <!-- begin col-6 -->
                                            <div class="col-6">
                                                <div class="f-s-12 text-grey">Gastos Totales</div>
                                                <div class="f-s-18 m-b-5 f-w-600 p-b-1">CRC <span data-animation="number" data-value="{{ round(session('purchases'),2) }}">0.00</span></div>
                                                <div class="progress progress-xs rounded-lg bg-dark-darker m-b-5">
                                                    <div class="progress-bar progress-bar-striped rounded-right" data-animation="width" data-value="55%" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <!-- end col-6 -->
                                        </div>
                                    </div>
                                    <!-- end col-5 -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col-6 -->
                    <!-- begin col-3 -->
                    <div class="col-xl-2 col-md-6">
                        <div class="widget widget-stats bg-gradient-red">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">COMPRA</div>
                                <div class="stats-number">{{ session('sale') }}</div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-xl-2 col-md-6">
                        <div class="widget widget-stats bg-gradient-indigo">
                            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                            <div class="stats-content">
                                <div class="stats-title">VENTA</div>
                                <div class="stats-number">{{ session('purchase') }}</div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <!-- end col-3 -->
                </div>
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
