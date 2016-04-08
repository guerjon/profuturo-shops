 <html>
 	<body>

	<h1>Se solicita un cambio de domicilio para el usuario {{$user->gerencia}} con CCOSTOS {{$user->ccosto}}</h1>	
		<br>
		<p>El domicilio actual del usuario es </p>
		<br>
		<h3>{{$address->domicilio}}</h3>
		<br>
		Y se sugiere que la dirección correcta es esta.
		<br>
		<h3>{{$address->posible_cambio}}</h3>
		<br>
		Para aprobar este cambio de click aquí 
		<br>
		http://store.profuturocompras.com.mx/admin/users?active_tab=user_mac&user_mac%5Bemployee_number%5D={{$user->ccosto}}&user_mac%5Bgerencia%5D=
 	</body>
 </html>