@extends('layouts.master')

@section('content')

	<ol class="breadcrumb">
		<a href="{{URL::previous()}}" class="back-btn">
			<span class="glyphicon glyphicon-arrow-left"></span> Regresar
		</a>
			&nbsp;&nbsp;&nbsp;
		<li><a href="/">Inicio</a></li>
		<li><a href="/admin/reports/index">Reportes</a></li>
		<li class="active">Pedidos ventas</li>
	</ol>

	{{Form::open([
		'id' => 'filter-form',
		'method' => 'GET',
		'action' => 'AdminReportsController@getOrdersReport',
		])}}
		{{Form::hidden('page',null,['id' => 'number_page'])}}
		<div class="page-header">
			<h3>Reporte de pedidos ventas
			</h3>
		</div>

		<div class="row">
			<div class="col-xs-3 ">GERENCIA:
				{{Form::select('gerencia',array_merge(array(NULL => 'Seleccione una gerencia'),$gerencia),NUll,['class' => 'form-control'])}}
			</div>
			<div class="col-xs-3 ">CATEGORIA:
				{{Form::select('category_id',array_merge(array(NULL =>'Seleccione una Categoria'),$categories),NUll,['class' => 'form-control'])}}
			</div>
			<div class="col-xs-3 ">DESDE:
				{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
			</div>
			<div class="col-xs-3">
					NÚMERO DE PEDIDO
					{{Form::text('order_id',null,['class' => 'form-control','id' => 'order_id'])}} 
			</div>
		</div>
		<br>
		<div class="row">
			
			<div class="col-xs-3">LINEA DE NEGOCIO:
				{{Form::select('linea_negocio',[NULL => 'Seleccione una linea de negocio']+$business_line,NUll,['class' => 'form-control'])}}
			</div>
			<div class="col-xs-3">DIVISIONALES
				{{Form::select('divisional_id', [NULL => 'Todas las divisionales'] + Divisional::orderBy('id')->lists('name','id'), Input::get('gerencia'), ['class' => 'form-control'])}}
			</div>
			<div class="col-xs-3 ">HASTA:
					{{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
			</div>
			<div class="col-xs-3">
				<br>
				<button class="btn btn-primary" id="filter-btn" type="submit">
					<span class="fa fa-filter"></span>
					Filtrar
				</button>
				<button class="btn btn-primary btn-submit" type="button" id="excel-btn">
						<span class="glyphicon glyphicon-download-alt"></span> Descargar excel
				</button>
			</div>
		</div>
	{{Form::close()}}

	<hr>

	<div class="container-fluid">
		<div class="table-responsive">
			<table class="table table-responsive">
				<thead>
					<tr>
						@foreach($headers as $header)	
							<th>
								{{$header}}
							</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach($query as $order)
						<tr>
							@foreach($headers as $column)
								<td>
									{{$order->$column}}
								</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<center>
		{{$query->appends(Input::except('page'))->links()}}
	</center>
@stop

@section('script')
	<script src="/js/manual_pagination.js"></script>
	<script>
		$('#excel-btn').click(function(){
			$('#filter-form').append('<input name="xls" value="xls" id ="xls" class"hidden" type="hidden">');
			$('#filter-form').submit();
		});

		$('#filter-btn').click(function(event){
			event.preventDefault();
			$('#xls').remove();
			$('#filter-form').submit();
		});

	</script>
@stop