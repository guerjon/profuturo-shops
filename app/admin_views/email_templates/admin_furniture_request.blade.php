<html>
<head>
</head>
	<body>
		<center>
			<h2>Se han cotizado productos.</h2>
			<h2>Número de solicitud: {{$furniture_order->id}}</h2>
		</center>
		<hr>
		<h4>Producto solicitado: </h4> 

		<div width="100%">	
			Nombre: <mark>{{$furnitures[0]->request_description}}</mark><br>
			Cantidad : <mark>{{$furnitures[0]->request_quantiy_product}}</mark><br>
			Precio : <mark>{{$furnitures[0]->request_price}}</mark><br>
			Comentarios: <mark>{{$furnitures[0]->request_comments}}</mark> <br>
		</div>
		<hr>
		<h4>Productos cotizados: </h4>	
		@for($i = 1; $i < sizeof($furnitures);$i++)
			<div width="100%">
				Nombre: <mark>{{$furnitures[$i]->request_description}}</mark><br>
				Cantidad : <mark>{{$furnitures[$i]->request_quantiy_product}}</mark><br>
				Precio : <mark>{{$furnitures[$i]->request_price}}</mark><br>
				Comentarios: <mark>{{$furnitures[$i]->request_comments}}</mark> <br>				
			</div>
			<hr>
		@endfor
		
		Para seleccionar algun producto de esta solicitud de click <a href="store.profuturocompras.com.mx/furniture-requests/{{$furniture_order->id}}">aquí</a>
 	</body>
</html>