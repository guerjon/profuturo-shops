<ul class="nav nav-pills nav-stacked">
  @if(!Auth::check())
  <li role="presentation" class="disabled"><a href="#">Inicia sesión para acceder al contenido</a></li>
  @else
	
	  @foreach(Auth::user()->menu_actions as $action => $name)
	  	@if(Auth::user()->role == "admin")

		  	@if($name == "Categorías")	
				<li class="" >
					<a href="#"  class="submenu-dispatch accordion-toggle"
					  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-categoria">
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
				</ul>
			</li>
			@elseif($name == "Productos")
				<li class=" {{ substr(Route::currentRouteName(), 0, 5)=='tabla'?'active':''}}">
						<a href="#"  class="submenu-dispatch accordion-toggle"
						  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-producto">
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
					</ul>
				</li>
			@elseif($name == "Pedidos")
				<li class="">
					<a href="#"  class="submenu-dispatch accordion-toggle"
					  data-toggle="collapse" data-parent="#menu-content" data-target="#tipo-pedido">
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
					</ul>
				</li>
			
		@endif
			@if($name != "Categorías" and $name != "Pedidos" and $name != "Productos" )
				<li role="presentation">{{link_to($action, $name)}}</li>
			@endif
		@else
			<li role="presentation">{{link_to($action, $name)}}</li>
		@endif
  

  @endforeach
   
  @endif

</ul>
