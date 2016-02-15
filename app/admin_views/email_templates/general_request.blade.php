	 <html>
	 	<body>

		<p>{{$estado}}</p>
		@if(isset($base))
		http://store.profuturocompras.com.mx/solicitudes-generales
	 	@endif
	 	http://store.profuturocompras.com.mx/encuesta-satisfaccion/questions/{{$request->id}}
	 	</body>
	 </html>