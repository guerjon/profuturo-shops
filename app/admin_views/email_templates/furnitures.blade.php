	 <html>
	 	<body>

		<div class="welcome">
			Numero de orden: {{$order->id}}
			<br>
			@foreach($order->products as $product) 
     		<br>
     		Producto: {{$product->name}}
     		<br>
     		Cantidad:{{$product->pivot->quantity}}
				<br>
			@endforeach
		
		</div>
	
	 	</body>
	 </html>