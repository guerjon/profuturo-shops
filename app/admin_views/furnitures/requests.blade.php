@extends('layouts.master')

@section('content')

	<ol class="breadcrumb">
	  <a href="{{URL::previous()}}" class="back-btn">
	    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
	  </a>
	    &nbsp;&nbsp;&nbsp;
	  <li><a href="/">Inicio</a></li>
	  <li class="active">Solicitudes sistema</li>
	</ol>

	<div class="container-fluid">
		@if($requests->count() > 0)
			<table class="table table-striped">
				<thead>
					<th>
						Número de solicitud
					</th>
					<th>
						Fecha de solicitud
					</th>
					<th>
						Estatus
					</th>
					<th>
						Usuario
					</th>
				</thead>
				<tbody>
					@foreach($requests as $request)
						<tr>
							<td>
								<a href="{{action('AdminFurnitureRequestsController@show',$request->id)}}">{{$request->id}}</a> 
							</td>
							<td>
								{{$request->created_at}}
							</td>
							<td>
								{{$request->readable_status}}
							</td>
							<td>
								{{$request->user->gerencia}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-info">
				Sin solicitudes.
			</div>
		@endif
	</div>
@endsection