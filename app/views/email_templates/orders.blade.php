<html>
	<head>
	</head>
	<body>
		
		<img src="{{$message->embed(public_path('/img/logo-header-profuturo.png'))}}"> 
		<hr>
		<table>
			<tbody>
				<tr>
					<td width="450">
						Estimado <strong>  {{$user->nombre}}  </strong> 
					</td>
					<td>
						Confirmación de orden
					</td>
				</tr>
				<tr>
					<td width="450">
						Gracias por usar el sistema Profuturo Insumos estratégicos <br>
						Por favor revise que su pedido tenga los datos correctos, <br>
						en caso contrario avise al administrador. <br>								
					</td>
					<td>
						<table class="table table-striped" border="1">
							<thead bgcolor="#1e366c" >
								<th>
									<font color="white">
										Detalles	
									</font>
								</th>
								<th></th>
							</thead>
							<tbody>
								<tr>
									<td>
										Fecha
									</td>
									<td>
									 	{{$order->created_at}}  
									</td>
								</tr>
								<tr>
									<td>
										Número de orden
									</td>
									<td>
									 <font color="red">{{$order->id}}</font>   
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>

			</tbody>	
		</table>
		<br><br>
		<table border="1">
			<thead bgcolor="#1e366c" width="200">
				<th>
					<font color="white">
						Detalles de envio y entrega	
					</font>
				</th>
				<th></th>
			</thead>
			<tbody>
				<tr width="200">
					<td>
						CC
					</td>
					<td width="250">
						<strong> 	{{$user->ccosto}}  </strong> 
					</td>
				</tr>
				<tr width="200">
					<td>
						Orden completa
					</td>
					<td width="250">
						SI
					</td>
				</tr >
				<tr width="250">
					<td>
						Dirección
					</td>
					<td>
						{{$user->address ? $user->address->domicilio : 'Sin domicilio registrado.'}} 
					</td>
				</tr>
			</tbody>
		</table>
		<br><br><br>
		<hr>
		<h3>DETALLE DE LA ORDEN</h3>
		<table border="1">
			<thead bgcolor="#1e366c">
				
				<th width="50"><font color="white"> Cantidad </font></th>	
				<th width="200"><font color="white"> Descripción </font></th>
				<th width="200"><font color="white">ID people </font></th>
			</thead>
			<tbody>
			@foreach($order->products as $product)
					<tr width="450">
						<td>
							{{$product->pivot->quantity}}
						</td>
						<td>
							{{$product->name}}
						</td>
						<td>
							{{$product->id_people}}
						</td>
					</tr>
				@endforeach		
			</tbody>
		</table>
		<center>
			<mark>
				<a href="http://store.profuturocompras.com.mx/login">Insumos estratégicos</a>		
			</mark>				
		</center>
		<hr>

	</body>
</html>
