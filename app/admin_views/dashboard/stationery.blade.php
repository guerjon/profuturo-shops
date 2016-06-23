@extends('layouts.master')
	
@section('content')

    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
        &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li class="active">Dashboard Papeleria</li>
    </ol>

    {{ Form::open([
        'id' => 'filters-form',
        'class' => 'form-horizontal'
    ]) }}
        <div class="form-group">
            <label class="control-label col-md-1">
                Desde
            </label>
            <div class="col-md-3">
                {{ Form::text('from', \Carbon\Carbon::now()->startOfMonth()->subYear()->format('Y-m-d'), ['id' => 'from', 'class' => 'form-control']) }}
                
            </div>
            <label class="control-label col-md-1">
                Hasta
            </label>
            <div class="col-md-3">

                {{ Form::text('to', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), ['id' => 'to', 'class' => 'form-control']) }}
                
            </div>
            <div class="col-md-4">
                <!-- <div class="text-center"> -->
                    <button type="submit" class="btn btn-default">
                        <span class="fa fa-search"></span> Filtrar
                    </button>
                <!-- </div> -->
            </div>
        </div>  

    {{ Form::close() }}

    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <span class="fa fa-truck dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Pedidos</h3>
                            <span id="orders" class="panel-big-text"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <span class="fa fa-check dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Gerencias con pedido</h3>
                            <span id="people" class="panel-big-text"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <span class="fa fa-usd dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Monto gastado</h3>
                            <span id="total" class="panel-big-text"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <span class="fa fa-users dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Promedio por gerencia</h3>
                            <span id="avg" class="panel-big-text"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <span class="fa fa-industry dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Promedio por pedido</h3>
                            <span id="avg-order" class="panel-big-text"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        # de pedidos por categoría
                    </h3>
                </div>
                <canvas id="categories-overview"></canvas>
            </div>
        </div>
        
        <div class="col-md-4 hide">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Pedidos categoría <span id="orders-by-cat-title"></span>
                    </h3>
                </div>
                <table id="orders-by-cat-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="orders-by-cat-pagination"></div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Top 5 Categorías
                    </h3>
                </div>
                <table id="top-categories" class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                Categoría
                            </th>
                            <th>
                                Productos solicitados
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Top 5 Productos
                    </h3>
                </div>
                <table id="top-products" class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                Producto
                            </th>
                            <th>
                                # solicitados
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        # de pedidos por periodo
                    </h3>
                </div>
                <canvas id="annual-overview"></canvas>    
            </div>
            
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Pedidos del periodo <span id="period-title"></span>
                    </h3>
                </div>
                <table id="period-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="period-table-pagination"></div>
                
            </div>
        </div>
    </div>

    @section('style')
        {{ HTML::style('css/jquery-ui/blitzer/jquery-ui.min.css') }}
        {{ HTML::style('css/jquery-ui/blitzer/theme.css') }}
    @endsection


    @section('script')
    @parent
    	<script src="/js/laroute.js"></script>
        {{ HTML::script('js/jquery/ui/minified/core.min.js') }}
        {{ HTML::script('js/jquery/ui/minified/widget.min.js') }}
        {{ HTML::script('js/jquery/ui/minified/datepicker.min.js') }}
        {{ HTML::script('js/jquery/ui/minified/i18n/datepicker-es.min.js') }}
        {{ HTML::script('js/Chartjs/Chart.js') }}
        {{ HTML::script('js/numeral/numeral.min.js') }}
        {{ HTML::script('js/moment/moment-with-locales.min.js') }}
        {{ HTML::script('js/admin/dashboard.js') }}
    @endsection

@endsection