	 <html>
	 	<body>

		<div class="welcome">
			Numero de orden: {{$order->id}}
			<br>
			@foreach($order->products as $product) 
     		Producto: {{$product->name}}
     		<br>
     		Cantidad:{{$product->pivot->quantity}}
			@endforeach
		
		</div>
	
	 	</body>
	 </html>