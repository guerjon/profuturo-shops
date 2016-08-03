<html>
	<head>
	</head>
	<body>
		
		<a href="http://store.profuturocompras.com.mx/login">
			<img src="{{$message->embed(public_path('/img/logo-header-profuturo.png'))}}">
		</a>

		<hr>
		<table>
			<tbody>
				<tr>
					<td width="450">
						Estimad@: <strong>  {{$user->nombre}}  </strong> 
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
										<center>
											<font color="red">{{$order->id}}</font>   		
										</center>
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
				</th>
				<th>
					<font color="white">
						Detalles de envío y entrega	
					</font>
				</th>
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
				<th width="100"><font color="white">Condiciones de negocio</font> </th>
				<th width="100"><font color="white">Superficie </font></th>
			</thead>
			<tbody>
			@foreach($order->furnitures as $product)
				<tr width="450">
					<td>
						<center>
							{{$product->pivot->quantity}}	
						</center>
					</td>
					<td>
						{{$product->name}}
					</td>
					<td>
						{{$product->business_conditions}}
					</td>
					<td>
						{{$product->surface}}
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
