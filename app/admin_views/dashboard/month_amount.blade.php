@extends('layouts.master')
	
@section('content')

    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
        &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li><a href="{{action('AdminDashboardController@stationery')}}"> Dashboard ventas</a></li>
        <li class="active">Dashboard Por mes</li>
    </ol>
    <div class="row dashboard-first-row-panel-wrap">
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
                            <span id="people-orders" class="panel-big-text"></span>
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
                            <span class="fa fa-close dashboard-fa"></span>
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h3 class="panel-title">Gerencias sin pedido</h3>
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
    <h1>
        Monto gastado por mes
    </h1>
    <hr>
    <div class="row">    
        <div class="col-md-4">
            <div class="panel panel-primary table-wrap" style="height:301px;">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Productos mas solicitados
                    </h3>
                </div>
                <div class="panel-body">   
                    <table id="top-products" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Categoría
                                </th>
                                <th>
                                    Producto
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
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary table-wrap" style="height:301px;">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Productos menos solicitados
                    </h3>
                </div>
                <div class="panel-body">  
                    <table id="top-reverse-products" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Categoría
                                </th>
                                <th>
                                    Producto
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
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary table-wrap" style="height:301px;">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Gerencias con mayor monto gastado
                    </h3>
                </div>
                <div class="panel-body"> 
                    <table id="top-reverse-products" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                   CC
                                </th>
                                <th>
                                    Gerencia
                                </th>
                                <th>
                                    Region
                                </th>
                               <!--  <th>
                                    Monto
                                </th> -->
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-primary table-wrap">
    			<div class="panel-heading">
    				<h3 class="panel-title">
    					Todos los pedidos
    				</h3>
    			</div>
                <div class="panel-body">
                    <table id="all-orders" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    CC
                                </th>
                                <th>
                                    Gerencia
                                </th>
                                <th>
                                    Total productos
                                </th>
                                <th>
                                    Monto
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
    	</div>
    </div>

@endsection


    @section('style')
        {{ HTML::style('css/jquery-ui/blitzer/jquery-ui.min.css') }}
        {{ HTML::style('css/jquery-ui/blitzer/theme.css') }}
        {{ HTML::style('css/select2.css')}}
        {{ HTML::style('css/select2-bootstrap.css')}}
    @endsection


    @section('script')
    @parent
    	<script src="/js/laroute.js"></script>
        {{ HTML::script('js/select2.full.js')}}
        {{ HTML::script('js/jquery/ui/minified/core.min.js') }}
        {{ HTML::script('js/jquery/ui/minified/widget.min.js') }}

        {{ HTML::script('js/Chartjs/Chart.js') }}
        {{ HTML::script('js/numeral/numeral.min.js') }}
        {{ HTML::script('js/moment/moment-with-locales.min.js') }}
        {{ HTML::script('js/admin/dashboard.js') }}
        {{ HTML::script('js/admin/month.js') }}
    @endsection
