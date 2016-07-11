<html>
<head>
</head>
	<body>
		<center>
			<h2>El usuario {{$user->gerencia}} ha seleccionado un producto</h2>
			<h2>Número de solicitud: {{$furniture_order->id}}</h2>
		</center>
		<hr>
		<h4>Producto seleccionado: </h4> 

		<div width="100%">	
			Nombre: <mark>{{$furniture_selected[0]->request_description}}</mark><br>
			Cantidad : <mark>{{$furniture_selected[0]->request_quantiy_product}}</mark><br>
			Precio : <mark>{{$furniture_selected[0]->request_price}}</mark><br>
			Comentarios: <mark>{{$furniture_selected[0]->request_comments}}</mark> <br>
		</div>
		<hr>
		Para ver la solititud da click <a href="store.profuturocompras.com.mx/admin/solicitudes-mobilario/{{$furniture_order->id}}">aquí</a>
 	</body>
</html>