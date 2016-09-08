<ul class="nav nav-pills nav-stacked">
  @if(!Auth::check())
  <li role="presentation" class="disabled"><a href="#">Inicia sesión para acceder al contenido</a></li>
  @else
	
	  @foreach(Auth::user()->menu_actions as $action => $name_complete)
	  	<?php 
	  		$name_array = explode('|',$name_complete); 
	  		$name = $name_array[0];
	  		$icon = $name_array[1];
	  	?>

	  	@if(Auth::user()->role == "admin")

		  	@if($name == "Categorías")	
				<li class="" >
					<a href="#"  class="submenu-dispatch accordion-toggle"
					  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-categoria">
					    <i class="fa fa-th-large" aria-hidden="true"></i>
					    Categorías 
					    
					</a>
					<ul id="tipo-categoria" class="sub-menu collapse" >
						<li class="">
							<a href="{{action('AdminCategoriesController@index')}}">
								Categorías Productos
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminFurnitureCategoriesController@index')}}">
								Categorías Mobilario
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminMacCategoriesController@index')}}">
								
								Categorías MAC
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminCorporationCategoriesController@index')}}">	
								Categorías Corporativo
							</a>
						</li>
						<li>
							<a href="{{action('AdminTrainingCategoriesController@index')}}">	
								Categorías Capacitaciones
							</a>						
						</li>
					</ul>
				</li>
			@elseif($name == "Productos")
				<li class=" {{ substr(Route::currentRouteName(), 0, 5)=='tabla'?'active':''}}">
						<a href="#"  class="submenu-dispatch accordion-toggle"
						  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-producto">
						    <i class="fa fa-pencil" aria-hidden="true"></i>
						    Productos
						    
						</a>
					<ul id="tipo-producto" class="sub-menu collapse" >
						<li class="">
							<a href="{{action('AdminProductsController@index')}}">
								
								Productos Papelería
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminBusinessCardsController@index')}}">
								
								Productos Tarjetas
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminFurnituresController@index')}}">
								
								Productos Muebles
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminMacProductsController@index')}}">
								
								Productos MAC
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminCorporationProductsController@index')}}">
								Productos Corporativo
							</a>
						</li>
						<li>
							<a href="{{action('AdminTrainingProductsController@index')}}">
								Productos Capacitaciones
							</a>
						</li>
					</ul>
				</li>
			@elseif($name == "Pedidos")
				<li class="">
					<a href="#"  class="submenu-dispatch accordion-toggle"
					  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-pedido">
					    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
					    Pedidos
					    
					</a>
					<ul id="tipo-pedido" class="sub-menu collapse" >
						
							<li class="">		
								<a href="{{action('AdminOrdersController@index')}}">
									Pedidos papelería
								</a>	
							</li>
						

						<li class="">
							<a href="{{action('AdminBcOrdersController@index')}}">
								
								Pedidos tarjetas
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminFurnituresOrdersController@index')}}">
								
								Pedidos muebles
							</a>
						</li>
						<li class="">
							<a href="{{action('AdminMacOrdersController@index')}}">
								
								Pedidos MAC
							</a>
						</li>
						<li>
							<a href="{{action('AdminCorporationOrdersController@index')}}">
								
								Pedidos Corporativo
							</a>
						</li>
						<li>
							<a href="{{action('AdminTrainingOrdersController@index')}}">
								Pedidos Capacitaciones
							</a>
						</li>
						<li>
							<a href="{{action('AdminFurnitureRequestsController@index')}}">
								Solicitudes sistema
							</a>											
						</li>
					</ul>
				</li>
			@elseif($name  == 'Fechas')
				<li>
					<a href="#"  class="submenu-dispatch accordion-toggle"
					  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-fecha">
					    <i class="fa fa-calendar-o" aria-hidden="true"></i>
					    Fechas
					    
					</a>
					<ul id="tipo-fecha" class="sub-menu collapse">
						<li>
							<a href="{{action('AdminDivisionalController@index',['active_tab' => '1'])}}">
								Fechas papeleria
							</a>
						</li>
						<li>
							<a href="{{action('AdminDatesCorporationController@index')}}">
								Fechas corporativo
							</a>
						</li>
						<li>
							<a href="{{action('AdminDatesMacController@index')}}">
								Fechas MAC
							</a>
						</li>
						<li>
							<a href="{{action('AdminDatesTrainingController@index')}}">
								Fechas Capacitadores
							</a>
						</li>
					</ul>
				</li>
		@endif
			@if(!in_array($name,['Categorías','Pedidos','Productos','Fechas']))
				<li role="presentation">
					<a href="{{$action}}">
						<i class="fa {{$icon}}"></i>
						{{$name}}
						
					</a>
				</li>
			@endif

		@else
			<li role="presentation">{{link_to($action, $name)}}</li>
		@endif
  

  @endforeach
   
  @endif

</ul>
