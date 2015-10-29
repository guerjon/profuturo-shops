	 <html>
	 	<body>

		<div class="welcome">
			Numero de orden: {{$order->id}}
			
			@foreach($order->products as $product) 
     		{{$product->pivot->quantity}}
			@endforeach
		
		</div>
	
	 	</body>
	 </html>