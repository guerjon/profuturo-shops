@extends('layouts.master')

@section('content')

	<div class="container">

		<ol class="breadcrumb">
		    <a href="{{URL::previous()}}" class="back-btn">
		      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
		    </a>
		      &nbsp;&nbsp;&nbsp;
		    <li><a href="/">Inicio</a></li>
		    <li><a href="{{action('AdminCorporationCardsController@index')}}">Tarjetas corporativo </a></li>
		    <li class="active">Cargas tarjetas corporativo</li>
  		</ol>

		<div class="row">
			<br>
		<div class="col-xs-6">
			<h3>
				CARGAS TARJETAS CORPORATIVO
			</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a class="btn btn-primary" href="{{action("AdminCorporationCardsController@importCards")}}">
				<span class="glyphicon  glyphicon-plus"></span>
				Añadir carga
			</a>
			<a href="{{action('AdminUploadsController@downloadCorporationTemplate')}}" class="btn btn-primary">
				<span class="glyphicon glyphicon-import"></span>
				Descargar layout
			</a>			
		</div>
					
		</div>
		<hr>
		<div class="row">
			@if($uploads->count() > 0)
				<table class="table table-striped">
					<thead>
						<th>
							Usuario
						</th>
						<th>
							Archivo
						</th>
						<th>
							Hora de carga
						</th>
						<th>
							Registros creados
						</th>
						<th>
							Registros actualizados
						</th>
					</thead>
					<tbody>
						@foreach($uploads as $upload)
							<tr>
								<td>
									{{$upload->user->nombre}}
								</td>
								<td>
									<a href="{{action('AdminUploadsController@downloadOriginal', $upload->id)}}" >
										{{$upload->file_name}}	
									</a>
								<td>
									{{$upload->created_at}}
								</td>
								<td>
									{{$upload->cards_created}}
								</td>
								<td>
									{{$upload->cards_updated}}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="row">
					<center>
						{{$uploads->links()}}
					</center>
				</div>
			@else
				<div class="alert alert-info">
					Aún no existen cargas, para agregar una de click <a href="{{action('AdminCorporationCardsController@importCards')}}">aquí</a>
				</div>
			@endif
		</div>
	</div>


@endsection

