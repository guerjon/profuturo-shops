@include('admin::users.partials.filters')

@if($users_loader->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C. Costos</th>
          <th>Gerencia</th>
          <th>Región</th>
          <th>Línea de negocio</th>
          <th></th>
      
        </tr>
      </thead>

      <tbody>
        @foreach ($users_loader as $user_loader)
          <tr class="{{(Session::get('focus') == $user_loader->id) ? 'info' : ''}}">
             <td>{{$user_loader->ccosto}}</td>
             <td>{{$user_loader->gerencia}}</td>
             <td>{{$user_loader->region ? $user_loader->region->name : 'N/A'}}</td>
             <td>{{$user_loader->linea_negocio}}</td>  
            <td>
              @include('admin::users.partials.actions', ['user' => $user_loader])
            </td>
   
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_loader->appends(Input::only(['user_loader']) + ['active_tab' => 'user_loader'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
