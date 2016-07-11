<html>
<head>
</head>
	<body>
		<center>
			<h2>Solicitud generada por: {{$user->gerencia}}</h2>
			<h2>Número de solicitud: {{$furniture_order->id}}</h2>
		</center>
		<hr>
		<h4>Producto solicitado: </h4> 
		<div width="100%">	
			Nombre: <mark>{{$furniture[0]->request_description}}</mark><br>
			Cantidad : <mark>{{$furniture[0]->request_quantiy_product}}</mark><br>
			Precio : <mark>{{$furniture[0]->request_price}}</mark><br>
			Comentarios: <mark>{{$furniture[0]->request_comments}}</mark> <br>
		</div>
		<hr>
		Para cotizar productos para esta solicitud de click <a href="{{base_path('furniture-requests/'.$furniture_order->id)}}">aquí</a>
 	</body>
</html>