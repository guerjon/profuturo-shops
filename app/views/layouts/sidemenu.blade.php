<ul class="nav nav-pills nav-stacked">
  @if(!Auth::check())
  <li role="presentation" class="disabled"><a href="#">Inicia sesión para acceder al contenido</a></li>
  @else

  @foreach(Auth::user()->menu_actions as $action => $name)
  <li role="presentation">{{link_to($action, $name)}}</li>
  @endforeach

  @endif

</ul>
