@extends('layouts.master')

@section('content')
	
	<div class="container-fluid">
		
		<ol class="breadcrumb">
		    <a href="{{URL::previous()}}" class="back-btn">
		      	<span class="glyphicon glyphicon-arrow-left"></span> Regresar
		    </a>
		      	&nbsp;&nbsp;&nbsp;
		    <li><a href="/">Inicio</a></li>
		    <li class="active">Solicitudes sistema</li>
  		</ol>
		<div class="row">
			<div class="col col-xs-11">
				<h1>Solicitudes sistema</h1>
				<hr>
			</div>
			<div class="col col-xs-1">
				<a href="{{action('FurnitureRequestsController@create')}}" class="btn btn-primary">
					<span class="fa fa-plus"></span>
					Añadir solicitud
				</a>							
			</div>
		</div>
		<div class="row">
			@if($requests->count() > 0)
				<table class="table table-striped">
					<thead>
						<th>
							Orden
						</th>
						<th>
							Enviada
						</th>
						<th>
							Estatus
						</th>
					</thead>
					<tbody>
						@foreach($requests as $request)
							<tr>
								<td>
									<a href="{{action('FurnitureRequestsController@show',$request->id)}}">{{$request->id}}</a> 
								</td>
								<td>
									{{$request->created_at}}
								</td>
								<td>
									{{$request->status == 0 ? 
										'Enviado  <span style="color:#004a8d" class="fa fa-envelope-o"></span>' :
										'Aprobado <span style="color:green" class="fa fa-check"></span>'}}
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
	</div>

@endsection