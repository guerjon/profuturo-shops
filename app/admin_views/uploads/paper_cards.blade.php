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
		<div class="col-xs-8">
			<h3>
				CARGAS TARJETAS DE PAPELERIA
			</h3>
		</div>
		
		<div class="col-xs-4">
			<a class="btn btn-primary" href="business-cards/create">
				<span class="glyphicon  glyphicon-plus"></span>
				Añadir carga
			</a>
			<a href="{{action('AdminUploadsController@downloadPaperTemplate')}}" class="btn btn-primary">
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
									<a href="{{action('AdminUploadsController@downloadOriginal', $upload->id)}}">
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
					Aún no existen cargas, para agregar una de click <a href="business-cards/create">aquí</a>
				</div>
			@endif
		</div>
	</div>


@endsection

