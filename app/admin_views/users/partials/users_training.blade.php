@include('admin::users.partials.filters')

@if($users_training->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C. Costos</th>
          <th>Gerencia</th>
          <th>Región</th>
          <th>Extensión</th>
          <th>Domicilio</th>
          <th></th>
      
        </tr>
      </thead>

      <tbody>
        @foreach ($users_training as $user_training)
          <tr class="{{(Session::get('focus') == $user_training->id) ? 'info' : ''}}">
             <td>{{$user_training->ccosto}}</td>
             <td>{{$user_training->gerencia}}</td>
             <td>{{$user_training->region ? $user_training->region->name : 'N/A'}}</td>
             <td>{{$user_training->address ? $user_training->address->domicilio : 'N/A'}}
                @if($user_training->address ? ($user_training->address->posible_cambio != null) : false )
                  <button data-id="{{$user_training->address->id}}" data-domicilio="{{$user_training->address->domicilio}}" data-posible-cambio="{{$user_training->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
                    Ver cambio domicilio
                  </button>
                @endif
              </td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_training])
            </td>
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_training->appends(Input::only(['user_training']) + ['active_tab' => 'user_training'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
