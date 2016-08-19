@extends('layouts.master')
	
@section('content')

    <ol class="breadcrumb">
        <a href="{{URL::previous()}}" class="back-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
        &nbsp;&nbsp;&nbsp;
        <li><a href="/">Inicio</a></li>
        <li ><a href="{{action('AdminDashboardController@stationery')}}">Dashboard ventas</a></li>
        <li class="active"> Intermensual productos</li>
    </ol>
	
		{{ Form::open([
	        'id' => 'filters-form',
	        'class' => 'form-horizontal'
    	]) }}
	        <div class="form-group" id="inputs-to-annual">
	            <div class="col-md-2">
	                Mes inicio
                    
	                {{ Form::select('from_month',Lang::get('months'),\Carbon\Carbon::now()->startOfMonth()->subYear()->month-1, ['id' => 'from', 'class' => 'form-control']) }}                
                </div>
                <div class="col-md-2">
                    Año inicio
                    {{ Form::select('from_year',[
                            \Carbon\Carbon::now()->subYear()->year => \Carbon\Carbon::now()->subYear()->year,
                            \Carbon\Carbon::now()->year => \Carbon\Carbon::now()->year,
                            \Carbon\Carbon::now()->addYear()->year => \Carbon\Carbon::now()->addYear()->year],\Carbon\Carbon::now()->subYear()->year,
                         ['id' => 'from', 'class' => 'form-control']) }}                
	            </div>
	            <div class="col-md-2">
	                Mes final
	               {{ Form::select('to_month',Lang::get('months'),\Carbon\Carbon::now()->startOfMonth()->subYear()->month-1, ['id' => 'from', 'class' => 'form-control']) }}                
                </div>
                <div class="col-md-2">
                    Año final
                    {{ Form::select('to_year',[
                        \Carbon\Carbon::now()->subYear()->year => \Carbon\Carbon::now()->subYear()->year,
                        \Carbon\Carbon::now()->year => \Carbon\Carbon::now()->year,
                        \Carbon\Carbon::now()->addYear()->year => \Carbon\Carbon::now()->addYear()->year],\Carbon\Carbon::now()->startOfMonth()->subYear()->month-1, ['id' => 'from', 'class' => 'form-control']) }}
	            </div>
	            <div>
	                <br>
	                <button type="submit" class="btn btn-default">
	                    <span class="fa fa-search"></span> Filtrar
	                </button>
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
    <div class="row">
    	<div class="col-md-6">
            <div class="panel panel-primary" style="height:700px;">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Productos por mes Papeleria
                    </h3>
                </div>
                <canvas id="products" height="200" width- "300"></canvas>
                
            </div>
        </div>
		<div class="col-md-6">
			<div class="panel panel-primary" style="height:700px">
				<div class="panel-heading">
					<h3 class="panel-title">
						Productos por mes Mobiliario
					</h3>
				</div>
				<canvas id="furnitures"></canvas>
			</div>
		</div>
    </div>
    <div class="row">
    	<div class="col-md-6">
            <div class="panel panel-primary" style="height:700px;">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Productos por mes MAC
                    </h3>
                </div>
                <canvas id="mac_products"></canvas>
            </div>
        </div>
		<div class="col-md-6">
			<div class="panel panel-primary" style="height:700px">
				<div class="panel-heading">
					<h3 class="panel-title">
						Productos por mes Corporativo
					</h3>
				</div>
				<canvas id="corporation_products"></canvas>
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
    {{ HTML::script('js/admin/products_by_month.js') }}

@endsection