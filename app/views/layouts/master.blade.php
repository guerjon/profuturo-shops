<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Profuturo Insumos</title>

	<!-- Bootstrap -->
	<!--<link rel="stylesheet" href="/css/slidebars.css" media="screen" title="no title" charset="utf-8">-->
	<!--<link rel="stylesheet" href="/css/style.css" media="screen" title="no title" charset="utf-8">-->
	<link rel="stylesheet" href="/js/raty/lib/jquery.raty.css">
	{{ HTML::style('/css/app.css') }}
	{{ HTML::style('css/style.css') }}
	<link rel="stylesheet" href="/css/calendario.css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
	<link href="/css/select2.css" rel="stylesheet">
	<link href="/css/select2-bootstrap.css"  rel="stylesheet">
	<link rel="stylesheet" href="/css/font-awesome.min.css">
	<link rel="stylesheet" href="/css/app-sass.css">


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<![endif]-->
	@yield('style')
	
</head>
<body class="@if(isset($bodyClass)) {{$bodyClass}} @endif">
	<div class="sb-slidebar sb-left">
		@include('layouts.sidemenu')
	</div>

	<div id="sb-site">
		<nav class="navbar navbar-fixed-top navbar-default main-navbar" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button> -->
					<div class="navbar-brand-wrap">
						@if(Auth::check())
						<a class="navbar-brand" href="#">
							<div class="sb-toggle-left btn btn-default btn-lg sidemenu-toggle">
								<span class="glyphicon glyphicon-align-justify"></span>
							</div>
						</a>
						@endif
						<a href="/" class="navbar-brand">Insumos Estratégicos</a>
					</div>
					<div class="text-center">
						<img src="/img/logo-header-profuturo.png" alt="">
					</div>
					<ul class="nav navbar-nav navbar-right navbar-session">

						@if(Auth::check())

								<li>
									@if(Auth::user()->role == 'admin')
										<a href="{{action('AdminMessagesController@index')}}" class="message-type message-parent-image" data-type="recibidos" type="button" >
											{{HTML::image('/images/message.png',null,['id' => 'message','class' =>"message-image","style" => 'width:36px;height30px;'])}}  	
										</a>
									@else
										<a href="{{action('MessagesController@index')}}" class="message-type message-parent-image" data-type="recibidos" type="button" >
											{{HTML::image('/images/message.png',null,['id' => 'message','class' =>"message-image","style" => 'width:36px;height30px;'])}}  	
										</a>
									@endif
								</li>
							
							@if(Auth::user()->role == 'user_paper')
								<li>
									<a href="/carrito" style="font-size:32px" >
										<span class="glyphicon glyphicon-shopping-cart"></span>
									</a>
								</li>

							@elseif(Auth::user()->role == 'user_furnitures')
								<li>
									<a href="/carrito-muebles" style="font-size:32px">
										<span class="glyphicon glyphicon-shopping-cart"></span>
									</a>
								</li>

							@elseif(Auth::user()->role == 'user_mac' )
								<li>
									<a href="/carrito-mac" style="font-size:32px">
										<span class="glyphicon glyphicon-shopping-cart"></span>
									</a>
								</li>

							@elseif(Auth::user()->role == 'user_corporation')
								<li>
									<a href="/carrito-corporativo" style="font-size:32px">
										<span class="glyphicon glyphicon-shopping-cart"></span>
									</a>
								</li>
							@elseif(Auth::user()->role == 'user_training')
								<li>
									<a href="/carrito-capacitaciones" style="font-size:32px">
										<span class="glyphicon glyphicon-shopping-cart"></span>
									</a>
								</li>
							@endif

							<li class="dropdown">

								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									{{HTML::image(Auth::user()->image->url('mini'), Auth::user()->nombre, ['class' => 'img-rounded','style' => ' height: 30px;width: 30px;'] )}}
									
									{{Auth::user()->nombre}}
									| {{Auth::user()->gerencia}}
									<span class="caret"></span>

								</a>

								<ul class="dropdown-menu" role="menu">
								@if(Auth::user()->is_admin)
											<li><a href="/perfil">Mi perfil</a></li>

									@elseif(Auth::user()->ismanager)
											<li><a href="/perfil">Mi perfil</a></li>
									@elseif(Auth::user()->user_paper)
										<li><a href="/perfil">Mi perfil</a></li>
										<li><a href="/pedidos">Mis pedidos</a></li>
										<li><a href="/carrito">Mi carrito</a></li>
									@elseif(Auth::user()->user_furnitures)                  
										<li><a href="/perfil">Mi perfil</a></li>
										<li><a href="/pedidos-muebles">Mis pedidos</a></li>
									@else
										<li><a href="/perfil">Mi perfil</a></li>
								@endif
									<li class="divider"></li>
									<li><a href="/logout">Cerrar sesión</a></li>
								</ul>
							</li>
						
						@else
							<li class="login-li"><a href="/login">Iniciar sesión</a></li>
						@endif
					</ul>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				</div> -->
			</div>
		</nav>
		<img src="/img/icon-barra-superior.png" alt="">
		<div class="container-fluid">

			@foreach(['info', 'success', 'warning','danger'] as $val)
				@if(Session::has($val))
				<div class="alert alert-{{$val}}">
					{{Session::pull($val)}}
				</div>
				@endif
			@endforeach
	
			@yield('content')
	
		</div>
	</div>

	<!--jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/js/laroute.js"></script>
	<script src="/js/bootstrap/bootstrap.min.js"></script>
	<script src="/js/raty/lib/jquery.raty.js"></script>
	<script src="http://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
	<script src="http://cdn.jsdelivr.net/jquery.validation/1.15.1/additional-methods.min.js"></script>
	<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script> 
	<script src="/js/slidebars.js"></script>
	<script src="/js/jquery.datetimepicker.js"></script>
	<script src="/js/download.js"></script>
	<script type="text/javascript"
	 src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
	<script type="text/javascript" src="/js/jquery.bootpag.js"></script>
	<script type="text/javascript" src="/js/date-picker-configuration.js"></script>
	<script src="/js/select2.full.js"></script>

	<script charset="utf-8">

	      		(function worker() {
				  $.ajax({
				    url: '/api/count-messages/', 
				    success: function(data) {
				    	if(data.number_messages > 0)
				    		if(($('.message-parent-image').find('.numberCircle')).length == 0 )
				    			$('.message-parent-image').append('<span class="numberCircle">'+data.number_messages+'</span>')
				      	else{
				      		if(data.number_messages < 1){
				      			$('.numberCircle').remove();
				      		}
				      	}
				    },
				    complete: function() {
				      setTimeout(worker, 5000);
				    }
				  });
				})();

			$(function(){
				$.slidebars();
				window.magic_select = $('#search-ccostos').clone();

				$('.notification-link').click(function(event){
					$.post('/admin/api/notification-marker',{id:$(this).attr('data-notification-id')},function(data){
						if(data.status == 200){
							$(this).click()
						}
					});
				});

				$(".js-example-basic-multiple").select2({
					 "language": {
				       "noResults": function(){
				           return "No se encontraron resultados.";
				       }
					 }
				});

			});
		</script>
	@yield('script')

	<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		  ga('create', 'UA-80720797-1', 'auto');
		  ga('send', 'pageview');
	</script>	

	
</body>
</html>
