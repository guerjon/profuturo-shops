@extends('layouts.master')

@section('content')
	
	<div class="container-fluid">
		
		<ol class="breadcrumb">
		    <a href="{{URL::previous()}}" class="back-btn">
		      	<span class="glyphicon glyphicon-arrow-left"></span> Regresar
		    </a>
		      	&nbsp;&nbsp;&nbsp;
		    <li><a href="/">Inicio</a></li>
		    <li><a href="/furniture-requests">Solicitudes Sistema</a></li>
		    <li class="active">Detalle solicitud {{$request->id}}</li>
  		</ol>
		<div class="row">
			<div class="col col-xs-11">
				<h1>Solicitudes sistema</h1>
				<hr>
			</div>
		</div>
	@if($request->status == 2)
		<div class="row">
			<div class="col col-xs-4 col-xs-offset-4">
				<div class="wrapper">
			        <div class="pricing-table group"> 
			            <div class="block professional fl">
			                <h2 class="title">Producto seleccionado</h2>
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

		<div class="row">
			<div class="col col-xs-3">
				<div class="wrapper" >
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
	    	
    		@if($request->furnitures->count() > 1)
	    		<div id="products-added" class="{{$request->furnitures->count() > 1 ? '': 'hidden'}} ">
	    			<h3>Productos Cotizados</h3>
	    			@for($i=1;$i < sizeof($request->furnitures);$i++)
		    				<div class="col col-xs-3" >
		    					<a href="#" class="select-product-btn" data-request-id="{{$request->id}}" data-product-id="{{$request->furnitures[$i]->pivot->request_product_id}}" data-toggle="bottom" data-placement="auto" title="Seleccionar producto">
			    					<div class="col col-xs-10 jumbotron products-added">
				    					Nombre del producto: <mark>{{$request->furnitures[$i]->pivot->request_description}}</mark>
				    					<br>
				    					Precio del producto: <mark>{{$request->furnitures[$i]->pivot->request_price}}</mark> 
				    					<br>
				    					Cantidad: <mark>{{$request->furnitures[$i]->pivot->request_quantiy_product}}</mark> 
				    					<br>
				    					Comentarios <mark>{{$request->furnitures[$i]->pivot->request_comments}}</mark> 
				    					<br>	    						
			    					</div>
		    					</a>
		    				</div>	    					
	    			@endfor    				
				</div>
    		@else
    			<div class="col col-xs-9">	
    				<h3>Productos Cotizados</h3>
	    			<div class="alert alert-info ">
	    				Aun no se han cotizados productos para esta solicitud.
	    			</div>
	    		</div>
    		@endif
    	</div>
	
	@endif

@include('furnitures/modals/select_product')
@endsection

@section('script')
	<script>
		$(function(){
			$('.select-product-btn').tooltip();

			$('.select-product-btn').click(function(){
				var modal = $('#select-product').modal().show();
				var request_id = $(this).attr('data-request-id');
				var action = laroute.action('FurnitureRequestController@update',{furniture_requests:request_id});
				$('#request_product_id').val($(this).attr('data-product-id'));
				modal.find('form').attr('action',action);
			});

			$('#accept-product-btn').click(function(){
				$('#select-product form').submit();
			});
		});
	</script>
@endsection