@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="{{action('AdminBusinessCardsController@index')}}">Tarjetas de Presentación</li>
    <li class="active">Cargas tarjetas papelería</li>
  </ol>


	<div class="container">
		<div class="row">
			<br>
		<div class="col-xs-9">
			<h3>
				CARGAS TARJETAS DE PAPELERIA
			</h3>
		</div>
		
		<div class="col-xs-3">
			<a class="btn btn-primary" href="business-cards/create">
				<span class="glyphicon  glyphicon-plus"></span>
				Añadir carga
			</a>
			<a href="#" class="btn btn-primary">
				<span class="glyphicon glyphicon-import"></span>
				Descargar plantilla
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
						<th>
							
						</th>

					</thead>
					<tbody>
						@foreach($uploads as $upload)
							<tr>
								<td>
									{{$upload->user->nombre}}
								</td>
								<td>
									{{$upload->file_name}}
			                        
								<td>
									{{$upload->created_at}}
								</td>
								<td>
									{{$upload->cards_created}}
								</td>
								<td>
									{{$upload->cards_updated}}
								</td>
								<td>
									<a href="{{action('AdminUploadsController@downloadOriginal', $upload->id)}}" class="list-group-item" id="download-original">
			                            <span class="fa fa-download"></span> Descargar original				
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
					Aún no existen cargas, para agregar una de click <a href="business-cards/create">aquí</a>
				</div>
			@endif
		</div>
	</div>


@endsection

