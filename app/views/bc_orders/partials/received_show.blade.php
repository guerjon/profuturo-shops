<div class="container-fluid">
	<div class="well">
		Pedido recibido el día {{$bc_order->updated_at->format('d-m-Y')}}
	</div>

	<div class="table-responsive" >
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>
						Nombre de empleado
					</th>
					<th>
						Número de empleado
					</th>
					<th>
						CCosto
					</th>
					<th>
						Puesto
					</th>
					<th>
						Gerencia  
					</th>
					<th>
						Dirección
					</th>
					<th>
						Dirección alternativa
					</th>
					<th>
						Telefono
					</th>
					<th>
						Celular
					</th>
					<th>
						Email
					</th>
					<th>
						Web
					</th>
				</tr>
			</thead>

			<tbody>
				@foreach($bc_order->business_cards as $card)
					<tr>
						<td>
							{{$card->nombre}}
						</td>
						<td>
							{{$card->no_emp}}
						</td>
						<td>
							{{$card->ccosto}}
						</td>
						<td>
							{{$card->nombre_puesto}}
						</td>
						<td>
							{{$card->gerencia}}
						</td>
						<td>
							{{$card->direccion}}
						</td>
						<td>
							{{$card->direccion_alternativa}}
						</td>
						<td>
							{{$card->telefono}}
						</td>
						<td>
							{{$card->celular}}
						</td>
						<td>
							{{$card->email}}
						</td>
						<td>
							{{$card->web}}
						</td>
					</tr>
				@endforeach
				@if($blank_card)
					<tr>
						<td>
							Tarjetas blancas
						</td>
						<td>
							{{$blank_card->quantity}}
						</td>
					</tr>
				@endif
				@if($bc_order->extra)
					<tr>
						<td>
							{{$bc_order->extra->talento_nombre}}
						</td>
					</tr>
					<tr>
						<td>
							{{$bc_order->extra->gerente_nombre}}
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>