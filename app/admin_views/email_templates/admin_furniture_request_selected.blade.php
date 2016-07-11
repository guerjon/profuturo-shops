
 <html>
	<body>
		<center>
			<h1>Solicitud de sistemas número {{$furniture_order->id}} <br>
				solicitada hecha por {{$user->gerencia}}</h1>
			<hr>
			<h4>El usuario selecciono el siguiente producot</h4> 
			<hr>
		</center>
		<br>		
		<div class="jumbotron">
			<div class="col col-xs-6 col-xs-offset-3">
				
				Producto : <mark>{{$furniture_selected[0]->request_description}}</mark><br>
				Precio : <mark> {{$furniture_selected[0]->request_price}}</mark>
				Cantidad : <mark> {{$furniture_selected[0]->request_quantiy_product}} </mark><br>
				Comentarios: <mark> {{$furniture_selected[0]->request_comments}} </mark><br>

			</div>
		</div>
		Para ver el producto seleccionado dar click <a href="http://store.profuturocompras.com.mx/admin/solicitudes-mobilario/{{$furniture_order->id}}">aquí</a>.
 	</body>
 </html>