@extends('layouts.master')

@section('content')
	
	<div class="container-fluid">
		
		<ol class="breadcrumb">
		    <a href="{{URL::previous()}}" class="back-btn">
		      	<span class="glyphicon glyphicon-arrow-left"></span> Regresar
		    </a>
		      	&nbsp;&nbsp;&nbsp;
		    <li><a href="/">Inicio</a></li>
		    <li><a href="/admin/solicitudes-mobilario">Solicitudes Sistema</a></li>
		    <li class="active">Detalle solicitud {{$request->id}}</li>
  		</ol>
  		@if($request->status == 2)
			<hr>
			<div class="row">    		
				<div class="col col-xs-4 col-xs-offset-4">
					<div class="wrapper" style="margin:0%">
				        <div class="pricing-table group"> 
				            <div class="block professional fl">
				                <h2 class="title">Producto seleccionado por usuario</h2>
				                @foreach($request->furnitures as $furniture)
					                @if($furniture->pivot->request_product_id == $request->product_request_selected)
					                <ul class="features" style=" overflow: scroll;height: 300px;color:black;font-size:15px">
										<li>Nombre del producto: <mark>{{$furniture->pivot->request_description}}</mark>  <span class="fontawesome-star" id="actitud_consultor"></span ></li>
					                    <li>Precio del producto: <mark>{{number_format($furniture->pivot->request_price,2)}}</mark><span class="fontawesome-star" id="seguimiento_consultor"></span></li>
					                    <li>Cantidad : <mark>{{$furniture->pivot->request_quantiy_product}}</mark> <span class="fontawesome-star" id="tiempos_respuesta"></span></li>
					                    <li>Comentarios: <mark>{{$furniture->pivot->request_comments}}</mark> <span class="fontawesome-star" id="calidad_producto"></span></li>
					                </ul>
					                @endif
					            @endforeach
				            </div>
				        </div>
	    			</div>
	    		</div>		
	    	</div>		
  		@else
			{{Form::open(
				[
					'action' => ['AdminFurnitureRequestsController@update',$request->id],
					'method' => 'put',
					'id' => 'furniture-requests-products'
				])
			}}
				<hr>
				<div class="row">    		
					<div class="col col-xs-3">
						<div class="wrapper" style="margin:0%">
					        <div class="pricing-table group"> 
					            <div class="block professional fl">
					                <h2 class="title">Producto solicitado</h2>
					                <ul class="features" style=" overflow: scroll;height: 300px;color:black;font-size:15px">
										<li>Nombre del producto: <mark>{{$request->furnitures[0]->pivot->request_description}}</mark>  <span class="fontawesome-star" id="actitud_consultor"></span ></li>
					                    <li>Precio del producto: <mark>{{number_format($request->furnitures[0]->pivot->request_price,2)}}</mark><span class="fontawesome-star" id="seguimiento_consultor"></span></li>
					                    <li>Cantidad : <mark>{{$request->furnitures[0]->pivot->request_quantiy_product}}</mark> <span class="fontawesome-star" id="tiempos_respuesta"></span></li>
					                    <li>Comentarios: <mark>{{$request->furnitures[0]->pivot->request_comments}}</mark> <span class="fontawesome-star" id="calidad_producto"></span></li>
					                </ul> 
					            </div>
					        </div>
		    			</div>
		    		</div>
		    		<h3>Productos Cotizados</h3>
		    		
		    		
	    			<div id="products-added" class="{{$request->furnitures->count() > 1 ? '': 'hidden'}}">
		    			@for($i=1;$i < sizeof($request->furnitures);$i++)	
		    				<div class="col col-xs-3">
		    					<div class="col col-xs-10 jumbotron">
			    					Nombre del producto: <mark>{{$request->furnitures[$i]->pivot->request_description}}</mark>
			    					<br>
			    					Precio del producto: <mark>{{$request->furnitures[$i]->pivot->request_price}}</mark> 
			    					<br>
			    					Cantidad: <mark>{{$request->furnitures[$i]->pivot->request_quantiy_product}}</mark> 
			    					<br>
			    					Comentarios <mark>{{$request->furnitures[$i]->pivot->request_comments}}</mark> 
			    					<br>	    						
		    					</div>
		    				</div>
		    			@endfor    				
	    			</div>
	    			<div class="hidden" id="products-added-edit">
	    				@for($i=1;$i < sizeof($request->furnitures);$i++)
	    					<input type="text" class="hidden" name="request_product_id[]" value="{{$request->furnitures[$i]->pivot->request_product_id}}">
		    				<div class="col col-xs-3 products-added-edit">
		    					<div class="jumbotron">		
									<div class="form-group">
										<div class="row">
											<div class="col col-xs-6">
												{{Form::label('request_description','Nombre')}}
												{{Form::text('request_description[]',$request->furnitures[$i]->pivot->request_description,['class' => 'form-control','required'])}}
											</div>
											<div class="col col-xs-6">
												{{Form::label('request_price','Precio')}}
												{{Form::number('request_price[]',$request->furnitures[$i]->pivot->request_price,['class' => 'form-control','required'])}}	
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col col-xs-6">
												{{Form::label('request_quantiy_product','Cantidad')}}
												{{Form::number('request_quantiy_product[]',$request->furnitures[$i]->pivot->request_quantiy_product,['class' => 'form-control','required'])}}
											</div>
											<div class="col col-xs-6">
												
												{{Form::label('request_comments','Comentarios')}}
												{{Form::text('request_comments[]',$request->furnitures[$i]->pivot->request_comments,['class' => 'form-control','required'])}}
											
											</div>
										</div>
									</div>
								</div>	
		    				</div>
		    			@endfor    				
	    			</div>
		    		<div id="products" class="{{$request->furnitures->count() > 1 ? 'hidden': ''}}">
			    		<div class="col col-xs-3 furniture-input">
							<div class="jumbotron">		
								<div class="form-group">
									<div class="row">
										<div class="col col-xs-6">
											{{Form::label('request_description','Nombre')}}
											{{Form::text('request_description[]',null,['class' => 'form-control','required'])}}
										</div>
										<div class="col col-xs-6">
											{{Form::label('request_price','Precio')}}
											{{Form::number('request_price[]',null,['class' => 'form-control','required'])}}	
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col col-xs-6">
											{{Form::label('request_quantiy_product','Cantidad')}}
											{{Form::number('request_quantiy_product[]',null,['class' => 'form-control','required'])}}
										</div>
										<div class="col col-xs-6">
											
											{{Form::label('request_comments','Comentarios')}}
											{{Form::text('request_comments[]',null,['class' => 'form-control','required'])}}
										
										</div>
									</div>
								</div>
							</div>
			    		</div>
		    		</div>
			    	
				</div>
				<hr>
				
				<div class="text-center {{$request->furnitures->count() > 1 ? ''  : 'hidden'}}" >
					<button type="button" class="btn btn-primary" id="edit-products-btn" data-number-products="{{$request->furnitures->count()}}">
						<span class="fa fa-pencil"></span>
						Editar productos
					</button>						
				</div>
			
				<div class="row text-center {{$request->furnitures->count() > 1 ? 'hidden' :''}}" id="buttons-to-edit">
		
					<button type="button=" class="btn btn-primary" id="save-request">
						<span class="fa fa-save"></span>
							Guardar orden
					</button>
					<button type="button" class="btn btn-default" id="add-product">
						<span class="fa fa-plus"></span>
						Añadir producto
					</button>
					<button type="button" class="btn btn-danger hidden" id="delete-product">
						<span class="fa fa-trash"></span>
						Eliminar producto
					</button>	
					<button class="btn btn-default hidden" id="cancel" type="button">
						<span class="fa fa-close"></span>
						Cancelar
					</button>
				</div>				
				<input type="hidden" value="{{$request->furnitures->count() > 1 ? 1 : 0}}" name="is_edit">
				<button type="submit" class="hidden" id="btn-aux"></button>
			{{Form::close()}}
		@endif
	</div>
@include('admin::furnitures/modals/request_select_modal')

@endsection

@section('script')
	<style href="/css/table_style.css"></style>
	<script>
		$(function(){
			var times_clone = 0;
			var is_edit = 0;

			/**
			* permite añadir hasta 3 productos cotizados
			*/
			$('#add-product').click(function(){
				console.log(times_clone +' times to clone' );
				console.log(is_edit + ' is edit');

				if(times_clone == 0){
					var inputs = $('.furniture-input').first().clone();	
					inputs.find('input,textArea').val('');	
					$('#products').append(inputs);
					
				}else if(times_clone == 1){
					
					var inputs = $('.products-added-edit').first().clone();
					inputs.find('input,textArea').val('');	
					$('#products-added-edit').append(inputs);


				}else if(times_clone == 2){

					var inputs = $('.furniture-input').first().clone();	
					inputs.find('input,textArea').val('');	
					$('#products-added-edit').append(inputs);
					$(this).addClass('hidden');					

				}
				times_clone++;
			});

			$('#delete-product').click(function(){
				console.log(times_clone +' times to clone' );
				console.log(is_edit + ' is edit');
				if(is_edit == 0){
					var inputs = $('.furniture-input').last();
				}
				else{
					var inputs = $('.products-added-edit').last();
				}

				inputs.remove();	
				
				if(times_clone == 0){
					$(this).addClass('hidden');
				}
				else{
					$('#add-product').removeClass('hidden');
				}

				times_clone--;
			});

			$('#edit-products-btn').click(function(){
				times_clone = $(this).attr('data-number-products') - 1;
				is_edit = 1;

				console.log(times_clone +' times to clone' );
				console.log(is_edit + ' is edit');
				
				$('#products-added-edit').removeClass('hidden');
				$('#products-added').addClass('hidden');
				
				$(this).addClass('hidden');
				
				if(times_clone == 3){
					$('#add-product').addClass('hidden')	
					times_clone--;
				}
				$('#delete-product').removeClass('hidden')
				$('#buttons-to-edit').removeClass('hidden');
				$('#cancel').removeClass('hidden');
			});

			$('#save-request').click(function(){
				if(is_edit){
					$('#products').remove();
				}else{
					$('#products-added-edit').remove();
				}
				$('#btn-aux').trigger('click');
			});

			$('#cancel').click(function(){
				location.reload();
			});

		});
	</script>
@endsection