@extends('layouts.master')

@section('content')

@if($errors->count() > 0)
<div class="alert alert-danger">
	{{$errors->first()}}
</div>
@endif

@if($cards->count() == 0)
	<div class="alert alert-warning">
		No hay tarjetas de presentación disponibles
	</div>
@elseif(!$access)
	<br>
	<div class="col-xs-8 col-xs-offset-2">
		<div class="alert alert-info text-center">
				Su divisional no puede hacer pedidos por el momento o ya hizo la orden del mes.  
		</div>
	</div>
@else

<div class="container">
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
							Cantidad
						</th>
						<th>
							Inmueble
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
							@if(($last_order_date = @$forbidden[$card->id]) and $forbid)
								<small>
									No puede añadir esta tarjeta a su pedido puesto que ya hizo un pedido con fecha {{$last_order_date}}.

								</small>
							@else
							 {{ Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
							@endif
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
							{{Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
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
							{{Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
						</td>
						<td>
							{{Form::text('inmueble_gerente',null,['class' => 'form-control'])}}
						</td>

				</tr>

				</tbody>

			</table>

			<div class="text-right ">
				<button class="btn btn-lg btn-primary hide" id="next-button">
					Siguiente
					<i class="fa fa-arrow-right"></i>
				</button>
			</div>
		{{Form::close()}}
</div>

<br>

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


});
</script>
@stop
