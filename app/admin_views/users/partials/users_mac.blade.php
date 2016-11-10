@include('admin::users.partials.filters')

@if($users_mac->count() > 0)

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
        @foreach ($users_mac as $user_mac)
          <tr class="{{(Session::get('focus') == $user_mac->id) ? 'info' : ''}}">
             <td>{{$user_mac->ccosto}}</td>
             <td>{{$user_mac->gerencia}}</td>
             <td>{{$user_mac->region ? $user_mac->region->name : 'N/A'}}</td>
             <td>{{$user_mac->address ? $user_mac->address->domicilio : 'N/A'}}
                @if($user_mac->address ? ($user_mac->address->posible_cambio != null) : false )
                  <button data-id="{{$user_mac->address->id}}" data-domicilio="{{$user_mac->address->domicilio}}" data-posible-cambio="{{$user_mac->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
                    Ver cambio domicilio
                  </button>
                @endif
              </td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_mac])
            </td>
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_mac->appends(Input::only(['user_mac']) + ['active_tab' => 'user_mac'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
