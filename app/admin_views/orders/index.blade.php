@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
	<a href="{{URL::previous()}}" class="back-btn">
		<span class="glyphicon glyphicon-arrow-left"></span> Regresar
	</a>
	&nbsp;&nbsp;&nbsp;
	<li><a href="/">Inicio</a></li>
	<li class="active">Pedidos Papelería</li>
</ol>

@if($orders->count() == 0)
<div class="alert alert-info">
	No se han realizado pedidos todavía.
</div>
@else

	{{Form::open([
		'method' => 'GET',
		])}}
		
		<div class="row text-center">
			<div class="col col-xs-2">
				{{Form::label('ccosto','CCOSTOS')}}
				{{Form::number('ccosto', Input::get('ccosto'), ['class' => 'form-control', 'placeholder' => 'CCOSTOS'])}}
			</div>

			<div class="col col-xs-2">
				{{Form::label('gerencia','GERENCIA')}}
				{{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
			</div>
			<div class="col col-xs-2">
				{{Form::label('divisional_id','DIVISIONAL')}}			
				{{Form::select('divisional_id', [NULL => 'Todas las divisionales'] + Divisional::orderBy('id')->lists('name','id'), Input::get('divisional_id'), ['class' => 'form-control'])}}
			</div>
		
			<div class="col col-xs-2">
			    {{Form::label('until','DESDE')}}
			    {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
			</div>
			<div class="col col-xs-2">
				{{Form::label('to','HASTA')}}
				{{Form::text('to',\Carbon\Carbon::now('America/Mexico_City')->addDay(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'to' ])}}
			</div>

			<div class="col col-xs-2">
				<br>	
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-filter"></span> Filtrar
				</button>			
				<a href="{{action('AdminOrdersController@index', ['export'=>'xls'])}}" class="btn btn-primary btn-submit" style="float:right">
					<span class="glyphicon glyphicon-download-alt"></span> EXCEL
				</a>
			</div>

		</div>

	{{Form::close()}}
	
	<div class="container-fluid mt-30">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>
						Gerencia
					</th>
					<th>
						Centro de costos
					</th>
					<th>
						No. pedido
					</th>

					<th>
						Comentarios
					</th>
					<th>
						Fecha de pedido
					</th>
					<th>
						Estatus
					</th>
					<th>
						Domicilio
					</th>
					<th>
						Acciones
					</th>

				</tr>
			</thead>

			<tbody>
				@foreach($orders as $order)
				<tr>
					<td>
						{{$order->user->gerencia}}
					</td>
					<td>
						{{$order->user->ccosto}}
					</td>
					<td>
						{{link_to_action('AdminOrdersController@show', $order->order_id, [$order->order_id])}}
					</td>
					<td>
						{{$order->comments}}
					</td>
					<td>
						{{$order->order_date}}
					</td>
					<td>
						@if($order->status == 0)
						Pendiente
						@elseif($order->status==1)
						Recibido <span class="glyphicon glyphicon-check"></span>
						@elseif($order->status==2)
						Recibido incompleto.
						@if($complain = $order->order_complain)
						<small>{{$complain->complain}}</small>
						@endif
						@endif
					</td>

					<td>

						{{$order->user->address ? $order->user->address->domicilio : "N/A"}}
						@if($order->user->address ? ($order->user->address->posible_cambio != null) : false )
						<button data-id="{{$order->user->address->id}}" data-domicilio="{{$order->user->address->domicilio}}" data-posible-cambio="{{$order->user->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
							Ver cambio domicilio
						</button>
						@endif
					</td>
					<td>
						{{Form::open(array('action' =>['AdminOrdersController@destroy',$order->order_id],
						'method' => 'delete'))}}
						<button type="submit" class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove"></span> Eliminar
						</button>
						{{Form::close()}}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<center>
		{{$orders->appends(Request::except('page'))->links()}}
	</center>
	@endif
	@include('admin::address.partials.change_address')

	{{Form::close()}}
	@stop

	@section('script')
	<script>
		$(function(){
			$('#cambio').click(function(){
				var modal = $('#change-address').modal();

				$('#domicilio').text($(this).attr('data-domicilio'));
				$('#posible_cambio').text($(this).attr('data-posible-cambio'));
				var action = 'address/'+ $(this).attr('data-id');
				$('#change-address-form').attr('action',action);          
				modal.show();
			});


			$('.approve').click(function(){
				console.log($(this).attr('data-value'));
				if($(this).attr('data-value') == 1)
					$('#valor_aprobado').val(1);
				else
					$('#valor_aprobado').val(0);

				$('#change-address-form').submit();
			});
		});
	</script>

	@endsection
