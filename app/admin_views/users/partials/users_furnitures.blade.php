@include('admin::users.partials.filters')


@if($users_furnitures->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C. Costos</th>
          <th>Gerencia</th>
          <th>Región</th>
          <th>Divisional</th>
          <th>Línea de negocio</th>
          <th>Extensión</th>
          <th>Domicilio</th>
          <th>Número de empleado</th>
          <th></th>
      
        </tr>
      </thead>

      <tbody>
        @foreach ($users_furnitures as $user_furniture)
          <tr class="{{(Session::get('focus') == $user_furniture->id) ? 'info' : ''}}">
            <td>{{$user_furniture->ccosto}}</td>
            <td>{{$user_furniture->gerencia}}</td>
            <td>{{$user_furniture->region ? $user_furniture->region->name : 'N/A'}}</td>
            <td>{{$user_furniture->divisional ? $user_furniture->divisional->id : 'N/A' }}</td>
            <td>{{$user_furniture->linea_negocio}}</td>  
            <td>{{$user_furniture->extension}}</td>
            <td>{{$user_furniture->address ? $user_furniture->address->domicilio : 'N/A'}}
              @if($user_furniture->address ? ($user_furniture->address->posible_cambio != null) : false )
                <button data-id="{{$user_furniture->address->id}}" data-domicilio="{{$user_furniture->address->domicilio}}" data-posible-cambio="{{$user_furniture->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
                  Ver cambio domicilio
                </button>
              @endif
            </td>
            <td>
              {{$user_furniture->num_empleado}}
            </td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_furniture])
            </td>
   
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_furnitures->appends(Input::only(['user_furniture']) + ['active_tab' => 'user_furniture'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
