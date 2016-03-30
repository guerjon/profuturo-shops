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
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<![endif]-->
	</head>
	<body>
		<div class="sb-slidebar sb-left">
			@include('layouts.sidemenu')
		</div>




		<div id="sb-site">
			<nav class="navbar navbar-fixed-top navbar-default" role="navigation">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="navbar-brand" href="#">
							@if(Auth::check())
							<div class="sb-toggle-left btn btn-default btn-lg">
								<span class="glyphicon glyphicon-align-justify"></span>
							</div>
							@endif
							<img id="header" src="/img/header.png"></a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

						<ul class="nav navbar-nav navbar-right">

							@if(Auth::check())

								<li>
									<a href="#" class="message-type" data-type="enviados" type="button" >
											{{HTML::image('/images/message.png',null,['id' => 'message','class' =>"message-image","style" => 'width:36px;height30px;'])}}  
											<span class="numberCircle"></span>
									</a>
								</li>
								

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
								<li><a href="/login">Iniciar sesión</a></li>
							@endif
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
			<div class="container-fluid">

				@foreach(['info', 'success', 'warning'] as $val)
					@if(Session::has($val))
					<div class="alert alert-{{$val}}">
						{{Session::pull($val)}}
					</div>
					@endif
				@endforeach
				@yield('content')
				
			</div>
		</div>

		@include('pages.partials.message')
		<!--jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="/js/laroute.js"></script>
		<script src="/js/bootstrap/bootstrap.min.js"></script>
		<script src="/js/raty/lib/jquery.raty.js"></script>
		<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
		<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script> 
		<script src="/js/slidebars.js"></script>
		<script src="/js/jquery.datetimepicker.js"></script>
		<script src="/js/download.js"></script>
		<script type="text/javascript"
		 src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
		<script type="text/javascript" src="/js/jquery.bootpag.js"></script>
		<script type="text/javascript" src="/js/date-picker-configuration.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
		<script src="/js/messages.js"></script>

		<script charset="utf-8">


			$(function(){
				$.slidebars();
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

				$('#post-message-modal-button').click(function(){
					$('#post-message-modal-form').submit();
				});

				$('.message-type').click(function(){
					messages_update($(this).attr('data-type'));
				});

				window.setInterval(function(){
  					
				}, 5000);

				$(document).on('click', '.pagina_mensage', function(){
			        event.preventDefault();
			        var page = $(this).attr('data-page');
			        $('#number_page').val(page);
			        $('#pagination_message').empty();

			        if($('#enviados').parent().hasClass('active')){
			        	messages_update('enviados',page);
			        }else{
			        	messages_update('recibidos',page);
			        }
      			});

      			(function worker() {
				  $.ajax({
				    url: '/api/count-messages/', 
				    success: function(data) {
				      $('.numberCircle').text(data.number_messages);
				    },
				    complete: function() {
				      // Schedule the next request when the current one's complete
				      setTimeout(worker, 5000);
				    }
				  });
				})();
				    

			});
		</script>
		@yield('script')

		

		
	</body>
</html>
