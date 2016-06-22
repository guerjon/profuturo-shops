	 <html>
	 	<body>

		<div class="welcome">
		<center>
			<h1>Número de orden: {{$order->id}}</h1> 
			<br>
			<h2>Productos</h2>	
			@foreach($order->furnitures as $product) 
     		<br>
     		Producto: {{$product->name}}
     		<br>
     		Cantidad:{{$product->pivot->quantity}}
				<br>
				
			@endforeach
		</center>
		</div>
	
	 	</body>
	 </html>