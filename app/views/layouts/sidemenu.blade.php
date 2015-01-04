<ul class="nav nav-pills nav-stacked">
  @if(!Auth::check())
  <li role="presentation" class="disabled"><a href="#">Inicia sesión para acceder al contenido</a></li>
  @elseif(Auth::user()->is_admin)
  <li role="presentation"><a href="/admin/users">Usuarios</a></li>
  <li role="presentation"><a href="/admin/categories">Categorías</a></li>
  <li role="presentation"><a href="/admin/products">Productos</a></li>
  <li role="presentation"><a href="/admin/orders">Pedidos</a></li>
  @else
  <li role="presentation"><a href="/pedidos">Mis pedidos</a></li>
  <li role="presentation"><a href="/productos">Productos</a></li>
  <li role="presentation"><a href="/carrito">Mi carrito</a></li>
  @endif

</ul>
