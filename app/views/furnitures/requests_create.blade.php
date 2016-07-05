@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/furniture-requests">Solicitudes sistema</a></li>
  <li class="active">Crear solicitud</li>
</ol>


	<div class="container-fluid">
		<h1>
			Añadir productos a solicitud
		</h1>
		<hr>
		<div class="col col-xs-6 col-xs-offset-3" >
			{{Form::open(['action' => 'FurnitureRequestsController@store','id' => 'furniture-requests-products'])}}
				<div class="furniture-input jumbotron">		
					<div class="form-group">
						<div class="row">
							<div class="col col-xs-6">
								{{Form::label('request_description','Nombre')}}
								{{Form::text('request_description[]',null,['class' => 'form-control'])}}
							</div>
							<div class="col col-xs-6">
								{{Form::label('request_price','Precio')}}
								{{Form::number('request_price[]',null,['class' => 'form-control'])}}	
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col col-xs-6">
								{{Form::label('request_comments','Comentarios')}}
								{{Form::textArea('comments[]',null,['class' => 'form-control'])}}
							</div>
							<div class="col col-xs-6">
								
								{{Form::label('request_quantity','Cantidad')}}
								{{Form::number('request_quantity[]',null,['class' => 'form-control'])}}
							
							</div>
						</div>

					</div>
				</div>
			{{Form::close()}}			
					<div class="text-center">
						<button class="btn btn-info" id="add-product">
							<span class="fa fa-plus"></span>
							Añadir otro producto
						</button>
						<button class="btn btn-success" id="furniture-requests-button">
							<span class="fa fa-save"></span>
							Guardar
						</button>															
					</div>
			
		</div>
	</div>
@endsection

@section('script')
	<script>
		$(function(){
			var times_clone = 0
			$('#add-product').click(function(){
				if(times_clone < 3){
					var inputs = $('.furniture-input').first().clone();
					inputs.find('input,textArea').val('');
					$('#furniture-requests-products').append(inputs);
					times_clone++;
				}else{
					$('#furniture-requests-products').append('<div class="alert alert-danger">'+
						'El número de productos maximo para una solicitud es 4</div>');
				}
			});

			$('#furniture-requests-button').click(function(){
				$('#furniture-requests-products').submit();
			});
		});
	</script>
@endsection