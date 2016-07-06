@extends('layouts.master')

@section('content')
	
	<div class="container-fluid">
		
		<ol class="breadcrumb">
		    <a href="{{URL::previous()}}" class="back-btn">
		      	<span class="glyphicon glyphicon-arrow-left"></span> Regresar
		    </a>
		      	&nbsp;&nbsp;&nbsp;
		    <li><a href="/">Inicio</a></li>
		    <li><a href="/furniture-requests">Solicitudes Sistema</a></li>
		    <li class="active">Detalle solicitud {{$request->id}}</li>
  		</ol>
		<div class="row">
			<div class="col col-xs-11">
				<h1>Solicitudes sistema</h1>
				<hr>
			</div>
		</div>
		<div class="row">
			<table class="table table-striped">
				<thead>
					<th>
						Nombre producto
					</th>
					<th>
						Precio
					</th>
					<th>
						Cantidad
					</th>
					<th>
						Comentarios
					</th>
					<td>
						
					</td>
				</thead>
				<tbody>
					@foreach($request->furnitures as $furniture)
						<tr>
							<td>
								{{$furniture->pivot->request_description}}		
							</td>
							<td>
								$ {{number_format($furniture->pivot->request_price,2) }}
							</td>
							<td>
								{{$furniture->pivot->request_quantiy_product}}
							</td>
							<td>
								{{$furniture->pivot->request_comments}}
							</td>
							<td>

								@if($request->product_request_selected != 0)
									@if($request->product_request_selected == $furniture->pivot->request_product_id)
										Producto Seleccionado
										<span style="color:green" class="fa fa-check"></span>
									@endif
								@endif
							</td>					
						</tr>
						
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection