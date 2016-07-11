 <style>
	.plantilla {
	margin:0px;padding:0px;
	width:100%;
	box-shadow: 10px 10px 5px #888888;
	border:1px solid #000000;
	
	-moz-border-radius-bottomleft:6px;
	-webkit-border-bottom-left-radius:6px;
	border-bottom-left-radius:6px;
	
	-moz-border-radius-bottomright:6px;
	-webkit-border-bottom-right-radius:6px;
	border-bottom-right-radius:6px;
	
	-moz-border-radius-topright:6px;
	-webkit-border-top-right-radius:6px;
	border-top-right-radius:6px;
	
	-moz-border-radius-topleft:6px;
	-webkit-border-top-left-radius:6px;
	border-top-left-radius:6px;
}.plantilla table{
    border-collapse: collapse;
        border-spacing: 0;
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.plantilla tr:last-child td:last-child {
	-moz-border-radius-bottomright:6px;
	-webkit-border-bottom-right-radius:6px;
	border-bottom-right-radius:6px;
}
.plantilla table tr:first-child td:first-child {
	-moz-border-radius-topleft:6px;
	-webkit-border-top-left-radius:6px;
	border-top-left-radius:6px;
}
.plantilla table tr:first-child td:last-child {
	-moz-border-radius-topright:6px;
	-webkit-border-top-right-radius:6px;
	border-top-right-radius:6px;
}.plantilla tr:last-child td:first-child{
	-moz-border-radius-bottomleft:6px;
	-webkit-border-bottom-left-radius:6px;
	border-bottom-left-radius:6px;
}.plantilla tr:hover td{
	
}
.plantilla tr:nth-child(odd){ background-color:#ffffff; }
.plantilla tr:nth-child(even)    { background-color:#ffffff; }.plantilla td{
	vertical-align:middle;
	
	
	border:1px solid #000000;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:10px;
	font-size:10px;
	font-family:Arial;
	font-weight:bold;
	color:#000000;
}.plantilla tr:last-child td{
	border-width:0px 1px 0px 0px;
}.plantilla tr td:last-child{
	border-width:0px 0px 1px 0px;
}.plantilla tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}
.plantilla tr:first-child td{
		background:-o-linear-gradient(bottom, #000000 5%, #000000 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #000000), color-stop(1, #000000) );
	background:-moz-linear-gradient( center top, #000000 5%, #000000 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#000000", endColorstr="#000000");	background: -o-linear-gradient(top,#000000,000000);

	background-color:#000000;
	border:0px solid #000000;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:14px;
	font-family:Arial;
	font-weight:bold;
	color:#ffffff;
}
.plantilla tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #000000 5%, #000000 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #000000), color-stop(1, #000000) );
	background:-moz-linear-gradient( center top, #000000 5%, #000000 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#000000", endColorstr="#000000");	background: -o-linear-gradient(top,#000000,000000);

	background-color:#000000;
}
.plantilla tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}
.plantilla tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}

.boton {
	margin:5%;
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffffff), color-stop(1, #f6f6f6));
	background:-moz-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
	background:-webkit-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
	background:-o-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
	background:-ms-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
	background:linear-gradient(to bottom, #ffffff 5%, #f6f6f6 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0);
	background-color:#ffffff;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	cursor:pointer;
	color:#666666;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #ffffff;
}
.boton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f6f6f6), color-stop(1, #ffffff));
	background:-moz-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
	background:-webkit-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
	background:-o-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
	background:-ms-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
	background:linear-gradient(to bottom, #f6f6f6 5%, #ffffff 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f6f6f6', endColorstr='#ffffff',GradientType=0);
	background-color:#f6f6f6;
}
.boton:active {
	position:relative;
	top:1px;
}

</style>

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
		Para cotizar productos para esta solicitud de click <a href="http://store.profuturocompras.com.mx/admin/solicitudes-mobilario/{{$furniture_order->id}}">aquí</a>.
 	</body>
 </html>