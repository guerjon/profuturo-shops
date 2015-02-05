<ul class="nav nav-pills nav-stacked">
  @if(!Auth::check())
  <li role="presentation" class="disabled"><a href="#">Inicia sesión para acceder al contenido</a></li>
  <!-- elseif(Auth::user()->is_admin)
  <li role="presentation"><a href="/admin/users">Usuarios</a></li>
  <li role="presentation"><a href="/admin/categories">Categorías</a></li>
  <li role="presentation"><a href="/admin/products">Productos</a></li>
  <li role="presentation">
    <a href="/admin/business-cards">Tarjetas de presentación</a>
  </li>
  <li role="presentation"><a href="/admin/orders">Pedidos papelería</a></li>
  <li role="presentation"><a href="/admin/bc-orders">Pedidos tarjetas</a></li>
  <li role="presentation"><a href="/admin/agenda">Agenda</a></li>
  else
  <li role="presentation"><a href="/productos">Productos</a></li>
  <li role="presentation"><a href="/tarjetas-presentacion">Tarjetas de presentación</a></li>
  <li role="presentation"><a href="/carrito">Mi carrito (papelería)</a></li>
  <li role="presentation"><a href="/pedidos">Mis pedidos (papelería)</a></li>
  <li role="presentation"><a href="/pedidos-tp">Mis pedidos (tarjetas)</a></li>
  <li role="presentation"><a href="/solicitudes-generales">Solicitudes generales</a></li> -->
  @else

  @foreach(Auth::user()->menu_actions as $action => $name)
  <li role="presentation">{{link_to($action, $name)}}</li>
  @endforeach

  @endif

</ul>
