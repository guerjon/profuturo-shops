@extends('layouts.master')

@section('content')

	<div class="container">
		<div class="row">
			<br>
		<div class="col-xs-4">
			<h3>
				CARGAS
			</h3>
		</div>
		<div class="col-xs-6"></div>
		<div class="col-xs-2">
			<a class="btn btn-primary" href="business-cards/create">
				<span class="glyphicon  glyphicon-plus"></span>
				Añadir carga
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
									<a href="#">
										
									</a> 
								</td>
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
			@else
				<div class="alert alert-info">
					Aún no existen cargas, para agregar una de click <a href="business-cards/create">aquí</a>
				</div>
			@endif
		</div>
	</div>

@endsection

