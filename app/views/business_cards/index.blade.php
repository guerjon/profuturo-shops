@extends('layouts.master')

@section('content')

@if($errors->count() > 0)
<div class="alert alert-danger">
	{{$errors->first()}}
</div>
@endif

	<br>
<div class="container">
		<div class="row">
			{{Form::open([
			  'method' => 'GET',
			  'id' => 'cards-excel-form'
			])}}
				
			  	<div class="row">
					<div class="col-xs-3">
						<label for="no_emp">NÚMERO DE EMPLEADO</label>
					  	{{Form::number('no_emp', Input::get('no_emp'), ['class' => 'form-control', 'placeholder' => 'Número de empleado'])}}
					</div>
					<div class="col-xs-3">
					  	<label for="nombre">NOMBRE</label>
					  	{{Form::text('nombre',Input::get('nombre'),['class' => 'form-control','placeholder' => "Nombre"])}}
					</div>
					<div class="col-xs-3">
						<label for="nombre_puesto">PUESTO</label>
						{{Form::select('nombre_puesto', [NULL => 'Todas los puestos'] + BusinessCard::distinct()->lists('nombre_puesto','nombre_puesto'), Input::get('nombre_puesto'), ['class' => 'form-control'])}}
					</div>
					<div class="col-xs-3 text-right">
						<br>
						<button type="submit" class="btn btn-primary" id="cards-filter-button">
							<span class="glyphicon glyphicon-filter"></span> Filtrar
						</button>
						
						<a href="{{action('BusinessCardsController@index')}}" class="btn btn-default">
							<span class="fa fa-eraser"></span> Borrar filtros 
						</a> 
					</div>
			  	</div>
			  	<br>
			{{Form::close()}}
	  	</div>	
	@if($cards->count() > 0)

		@if( ! $access)
			<div class="col-xs-8 col-xs-offset-2">
				<div class="alert alert-info text-center" id="no-access-alert">
						Su divisional no puede hacer pedidos por el momento o ya hizo la orden del mes.  
				</div>
			</div>
		@endif
		{{Form::open([
			'action' => 'BcOrdersController@postFillOrder'
			])}}

				<table class="table-striped table">
					<thead>
						<tr>
							<th></th>
							<th>
								Número de empleado
							</th>

							<th>
								Nombre de empleado
							</th>
							<th>
								Puesto
							</th>
							<th>
								Gerencia a la que se enviarán
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach($cards as $card)
						<tr>
							<td style="max-width: 60px;" class="text-center">
								{{Form::checkbox("cards[]", $card->id, FALSE, ($forbid and @$forbidden[$card->id]) ? ['disabled' => true,'data-card-id' => $card->id,'class' => 'checkbox-target'] : ['data-card-id' => $card->id,'class' => 'checkbox-target'])}}
							</td>
							<td>
								{{$card->no_emp}}
							</td>
							<td>
								{{$card->nombre}}
							</td>
							<td>
								{{ $card->nombre_puesto }}
							</td>
							<td>
								{{Form::text("inmueble[$card->id]",null,['class' => 'form-control inmueble-input','data-card-id' => $card->id])}}
							</td>
						</tr>
						@endforeach
						<tr class=" escondido">
							 <td style="max-width: 60px;" class="text-center">
								{{Form::checkbox("talent[]", $card->id,null,['id' => 'talent'])}}
							</td>
							<td>
							 Talento
							</td>
							<td>

							</td>
							<td>
							</td>
							<td>
								{{Form::text('inmueble_talento',null,['class' => 'form-control'])}}
							</td>
					</tr>
					
					 <tr class="escondido">
							 <td style="max-width: 60px;" class="text-center">
								{{Form::checkbox("manager[]", $card->id,null,['id' => 'manager'])}}
							</td>
							<td>
								Gerente
							</td>
							<td>

							</td>
							<td>
							</td>
							<td>
								{{Form::text('inmueble_gerente',null,['class' => 'form-control'])}}
							</td>

					</tr>

					</tbody>

				</table>
				
				@if($access)
					<div class="text-right ">
						<button class="btn btn-lg btn-primary hide" id="next-button">
							Siguiente
							<i class="fa fa-arrow-right"></i>
						</button>
					</div>
				@endif
			{{Form::close()}}

	@else
	<div class="alert alert-warning">
		No se encontraron tarjetas de presentación disponibles
	</div>
	<br>

</div>

@endif


@stop

@section('script')
<script>

$(function(){

	$('.checkbox-target').click(function(){

		var id = $(this).attr('data-card-id');

		var inmueble = $('.inmueble-input[data-card-id='+id+']');
		$(this).is(':checked') ? inmueble.prop('required',true) : inmueble.prop('required',false);

		var seleccionados =	$(".checkbox-target:checked");
		console.log(seleccionados.length);
		if(seleccionados.length > 0)
			$('#next-button').removeClass('hide');
		else
			$('#next-button').addClass('hide');
	});
	
	if($('#no-access-alert').length) {
		$('form input').prop('disabled', true);
	}

});
</script>
@stop
