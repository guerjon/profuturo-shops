@extends('layouts.master')

@section('content')
	
	<ol class="breadcrumb">
		<a href="{{URL::previous()}}" class="back-btn">
		  <span class="glyphicon glyphicon-arrow-left"></span> Regresar
		</a>
		  &nbsp;&nbsp;&nbsp;
		<li><a href="/">Inicio</a></li>
		<li class="active">Tarjetas de corporativo</li>
	</ol>

<div class="container-fluid">
	
  	<div class="row">
		{{Form::open([
		  'method' => 'GET',
		  'id' => 'cards-excel-form'
		])}}
			<input type="hidden" value="{{$active_tab}}" name="active_tab">
		  	<div class="row">

				<div class="col-xs-2">
					<label for="no_emp">NÚMERO DE EMPLEADO</label>
				  	{{Form::number('no_emp', Input::get('no_emp'), ['class' => 'form-control', 'placeholder' => 'Número de empleado'])}}
				</div>
				<div class="col-xs-2">
					<label for="ccosto">CCOSTO</label>
				  	{{Form::number('ccosto', Input::get('ccosto'), ['class' => 'form-control', 'placeholder' => 'Centro de costos'])}}
				</div>
				<div class="col-xs-2">
				  	<label for="nombre">NOMBRE</label>
				  	{{Form::text('nombre',Input::get('nombre'),['class' => 'form-control','placeholder' => "Nombre"])}}
				</div>

				<div class="col-xs-2">
					<label for="gerencia">GERENCIA</label>
				  	{{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
				</div>
				<div class="col-xs-2">
				  	<label for="linea_negocio">LÍNEA DE NEGOCIO</label>
				  	{{Form::select('linea_negocio', [NULL => 'Todas las lineas'] + User::distinct()->where('role','!=','admin')->lists('linea_negocio','linea_negocio'), Input::get('linea_negocio'), ['class' => 'form-control'])}}
				</div>
				<div class="col-xs-2">
					<label for="nombre_puesto">PUESTO</label>
					{{Form::select('nombre_puesto', [NULL => 'Todas los puestos'] + BusinessCard::distinct()->lists('nombre_puesto','nombre_puesto'), Input::get('nombre_puesto'), ['class' => 'form-control'])}}
				</div>

		  	</div>
		  	<br>
		  	<div class="row text-center">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary" id="cards-filter-button">
						<span class="glyphicon glyphicon-filter"></span> Filtrar
					</button>
					@if($cards->count() > 0)
						<button class="btn btn-default" type="button" id="cards-excel-button">
							  <span class="fa fa-download"></span>
							  Descargar
						</button>
					@endif
					<a href="{{action('AdminCorporationCardsController@index',['active_tab' => $active_tab])}}" class="btn btn-default">
						<span class="fa fa-eraser"></span> Borrar filtros 
					</a>
					<a href="{{action('AdminUploadsController@corporationUploads')}}" class="btn btn-default">
					  	<span class="glyphicon glyphicon-folder-open"></span> Ver cargas 
					</a> 
				</div>
		  	</div>
		{{Form::close()}}
  	</div>
	

	



	<div class="row">

	<div class="col-xs-12">
		
	
		<div class="" style="margin: 20px inherit">
			<ul class="nav nav-tabs" role="tablist">

				<li role="presentation" class="{{$active_tab == 'untrashed' ? 'active' : ''}}">
					<a href="?active_tab=untrashed&page=1" class="tabs">
						Activos
					</a>
				</li>

				<li role="presentation" class="{{$active_tab == 'trashed' ? 'active' : ''}}">
				  	<a href="?active_tab=trashed&page=1" class="tabs">
				    	Inactivos
				  	</a>
				</li>
			</ul>
    	</div>		
	</div>
	</div>	
	@if($cards->count() <= 0)
		<div class="alert alert-info">
			No hay tarjetas disponibles
		</div>
	@else
		<div class="row">
			<div class="col-xs-12">
			<table class="table table-striped">
				<thead>
					<th>
					  	NÚMERO DE EMPLEADO
					</th>
					<th style="max-width: 200px">
					  	NOMBRE
					</th>
					<th>
					  	PUESTO
					</th>
					<th>
					 	CCOSTOS
					</th>
					<th>
					  	GERENCIA
					</th>
					<th>
					  	LÍNEA DE NEGOCIO
					</th>
					<th>
						WEB
					</th>
					<th style="max-width: 200px">
						DIRECCIÓN
					</th>
					<th>
						CREADA
					</th>
					<th>
						ACTUALIZADA
					</th>
					<th>

					</th>				
				</thead>
				<tbody>
					@foreach($cards as $card)
						<tr>
							<td>
							  {{$card->no_emp}}
							</td>
							<td style="max-width: 200px">
							  {{$card->nombre}}
							</td>
							<td>
							  {{$card->nombre_puesto}}
							</td>
							<td>
							  {{$card->ccosto}}
							</td>
							<td>
							  {{$card->gerencia}}
							</td>
							<td>
							  {{$card->linea_negocio}}
							</td>
							<td>
								{{$card->web}}
							</td >
							<td style="max-width: 200px">
								{{$card->direccion}}
							</td>
							<td>
								{{$card->created_at}}
							</td>
							<td>
								@if($card->created_at == $card->updated_at)
									SIN ACTUALIZACIÓN
								@else
									{{$card->updated_at}}
								@endif
							</td>

				            <td>
				              {{Form::open([
				                'class' => 'form-inline',
				                'action' => ['AdminCorporationCardsController@destroy', $card->id],
				                'method' => 'DELETE',
				                ])}}

				                @if($card->trashed())
				                  <button type="submit" class="btn btn-success btn-xs">
				                    <span class="glyphicon glyphicon-ok"></span> Habilitar
				                   </button>
				                @else
				                  <button type="submit" class="btn btn-danger btn-xs">
				                    <span class="glyphicon glyphicon-remove"></span> Inhabilitar
				                  </button>
				                @endif

				              {{Form::close()}}
				            </td>
						</tr>
					@endforeach
				</tbody>
				
			</table>
			</div>
		</div>
		<div class="row">
			<center>
				{{$cards->links()}}
			</center>
		</div>
	@endif
</div>
@endsection

@section('script')
  <script>
    $(function(){
      $('#cards-excel-button').click(function(){
          var form = $('#cards-excel-form');
          form.append('<input name="excel" value="1" type="hidden" id="input-hidden">');
          form.submit();
      });

	  $('#cards-filter-button').click(function(event){
	  	event.preventDefault();
	  	$('#input-hidden').remove();
	  	$('#cards-excel-form').submit();
	  });
    });
  </script>
@endsection