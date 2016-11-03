@extends('layouts.master')

@section('content')
	<br>
	<div class="row">
		<div class="col-xs-6">
		<ol class="breadcrumb">
			<a href="{{URL::previous()}}" class="back-btn">
			  <span class="glyphicon glyphicon-arrow-left"></span> Regresar
			</a>
			  &nbsp;&nbsp;&nbsp;
			<li><a href="/">Inicio</a></li>
			<li class="active">Tarjetas Corporativo</li>
		</ol>
		
			<h1>Tarjetas corporativo</h1>
		</div>
		<div class="col-xs-4">
		</div>
		<div class="col-xs-2">
			<a href="{{action("AdminCorporationCardsController@importCards")}}" class="btn btn-primary">
				<i class="fa fa-upload" aria-hidden="true"></i>
				Importar Excel
			</a>
		</div>	
		<hr>
	</div>
	<hr>
	<div class="row">
	<div class="col-xs-12">
		<center>
		{{Form::open([
		'method' => 'GET',
		'class' => 'form-inline'
		])}}
			<input type="hidden" value="{{$active_tab}}" name="active_tab">

			<div class="form-group">
			  {{Form::number('no_emp', Input::get('no_emp'), ['class' => 'form-control', 'placeholder' => '# de empleado'])}}
			</div>
			<div class="form-group">
			  {{Form::number('ccosto', Input::get('ccosto'), ['class' => 'form-control', 'placeholder' => 'Centro de costos'])}}
			</div>


			<div class="form-group">
			  {{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
			</div>

			<div class="form-group">
			  <button type="submit" class="btn btn-primary" >
			    <span class="glyphicon glyphicon-filter"></span> Filtrar
			  </button>
			</div>
		{{Form::close()}}	
		</center>	
	</div>	
		
	</div>

	<div class="row">
	<br>
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
						Número empleado
					</th>
					<th>
						Nombre empleado
					</th>
					<th>
						Nombre puesto
					</th>
					<th>
						Linea de negocio
					</th>
					<th>
						Web
					</th>
					<th>
						Centro de costos
					</th>
					<th>
						Gerencia
					</th>
					<th>
						Dirección
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
							<td>
								{{$card->nombre}}
							</td>
							<th>
								{{$card->nombre_puesto}}
							</th>
							<td>
								{{$card->linea_negocio}}
							</td>
							<td>
								{{$card->web}}
							</td>
							<td>
								{{$card->ccosto}}
							</td>
							<td>
								{{$card->gerencia}}
							</td>
							<td>
								{{$card->direccion}}
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

@endsection