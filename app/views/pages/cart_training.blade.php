@extends('layouts.master')


@section('content')
	@if(sizeof($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach($errors as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<br>
	@if($access)

		@if(Auth::user()->cart_training->count() == 0)
			
			<div class="alert alert-warning">
				Sin artículos en el carrito. Haga click <a href="/training-products" class="alert-link">aquí</a> para ver los productos disponibles.
			</div>
			
		@else

			<table class="table table-striped">

				<thead>
					<tr>
						<th>
							Producto
						</th>
						<th>
							Cantidad
						</th>
						<th>

						</th>
					</tr>
				</thead>

				<tbody>
					@foreach(Auth::user()->cart_training as $product)
					<tr>
						<td>
							{{$product->id == 10000  ? $product->pivot->description : $product->name}}
						</td>

						<td class="product-quantity">
							{{$product->pivot->quantity}}
						</td>

						<td>
							<button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-description="{{$product->pivot->description}}" data-product-id="{{$product->id}}" data-quantity="1">Eliminar 1</button>
							<button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-description="{{$product->pivot->description}}" data-product-id="{{$product->id}}" data-quantity="{{$product->pivot->quantity}}">Eliminar todos</button>

						</td>
					</tr>
					@endforeach

				</tbody>
			</table>
	
			<hr>

		@if(!Auth::user()->has_limit)
				
			&nbsp;
			{{Form::open([
				'action' => 'TrainingOrdersController@store',
				'role' => 'form',
				'id' => 'send-order-form'
				])}}

				{{Form::hidden('domicilio_original',$user->address ? $user->address->domicilio : null,['class' => 'appends'])}}
					<div class="row">
						<div class="col col-xs-6">
							<div class="form-group">
								{{Form::label('ccosto','COMENTARIOS')}}
								{{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 2])}}
							</div>
						</div>
						<div class="col col-xs-6">
							<div class="form-group">
								{{Form::label('ccosto','CCOSTO')}}
								{{Form::select('ccosto',User::lists('ccosto','ccosto'),null,['class' => 'form-control','id' => 'ccosto'])}}
							</div>    
						</div>             
					</div>
					<div class="form-group text-right">
						<button type="button" class="btn btn-warning btn-confirm" data-toggle="modal" data-target="#confirm-modal">Enviar pedido</button>
					</div>
			{{Form::close()}}
			@else  
					&nbsp;
					{{Form::open([
						'action' => 'TrainingOrdersController@store',
						'role' => 'form',
						'id' => 'send-order-form'
						])}}
		
						{{Form::hidden('domicilio_original',$user->address ? $user->address->domicilio : null,['class' => 'appends'])}}
						<div class="row">
							<div class="col col-xs-6">
								<div class="form-group">
									{{Form::label('ccosto','Sede')}}
									
									{{Form::select('ccosto', [null => 'Seleccione una sede'] + Lang::get('sedes'),null,['class' => 'form-control','id' => 'ccosto','required'])}}
								</div>    
							</div>               
							<div class="col col-xs-6">
								<div class="form-group">
									{{Form::label('ccosto','COMENTARIOS SOBRE LA ORDEN')}}
									{{Form::text('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden'])}}
								</div>
							</div>

						</div>
						<hr>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-warning btn-confirm" >Enviar pedido</button>
					</div>
					{{Form::close()}}
					
		 @endif
		@endif
	@else
		<div class="alert alert-info">
			Actualmente no se pueden realizar pedidos.
		</div>
	@endif

	@include('pages/partials/confirm')  
	@include('pages/partials/external_productos')  

@stop

@section('script')
<script charset="utf-8">
	$(function(){
		$('.table .btn-danger').click(function(){
			$.post('/api/remove-from-cart-training', {
				product_id : $(this).attr('data-product-id'),
				quantity : $(this).attr('data-quantity'),
				description: $(this).attr('data-description')
			}, function(data){
				if(data.status == 200){
					location.reload();
				}else{
					alert(data.error_msg);
				}
			});
		});

	$('#btn-accept').click(function(){
		 var cambio = $('#posible_cambio').clone();
		 cambio.attr('hidden',true);
		 cambio.appendTo('#send-order-form');
		$('#send-order-form').attr('action','/pedidos-capacitaciones');
		$('#send-order-form').submit();
	});

});

	//Clona y agrega el campo descripcion y cantidad a el formulario de la modal external-products
	$('#add_external_product').click(function(){
			var container = $('.description_container').first().clone();
			$('.description_container').last().after(container);
	});
	

	$('#save-extra-products').click(function(){
			$('#external-products form').submit();
	});

</script>
@stop
