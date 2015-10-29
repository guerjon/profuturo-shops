	 <html>
	 	<body>

		<div class="welcome">
			Numero de orden: {{$order->id}}
			
			@foreach($products as $product)
					Cantidad {{$product->quantity}}
					Producto:{{$product->name}}
				@endforeach
	
		</div
	
	 	</body>
	 </html>