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
			{{Form::close()}}			
			<div class="text-center">
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
			$('#furniture-requests-button').click(function(){
				$('#furniture-requests-products').submit();
			});
		});
	</script>
@endsection