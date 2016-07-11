{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_system') }}
  <div class="form-group">
  
    <div class="col-xs-2">
      {{Form::text('user_system[employee_number]', (Input::get('user_system')['employee_number']), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
    </div>
    <div class="col-xs-2">
      {{Form::text('user_system[gerencia]', (Input::get('user_system')['gerencia']), ['placeholder' => 'Gerencia','class' => 'form-control'])}}
    </div>

    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}


@if($users_system->count() > 0)

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
        @foreach ($users_system as $user_system)
          <tr class="{{(Session::get('focus') == $user_system->id) ? 'info' : ''}}">
             <td>{{$user_system->ccosto}}</td>
             <td>{{$user_system->gerencia}}</td>
             <td>{{$user_system->region ? $user_system->region->name : 'N/A'}}</td>
             <td>{{$user_system->address ? $user_system->address->domicilio : 'N/A'}}
                @if($user_system->address ? ($user_system->address->posible_cambio != null) : false )
                  <button data-id="{{$user_system->address->id}}" data-domicilio="{{$user_system->address->domicilio}}" data-posible-cambio="{{$user_system->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
                    Ver cambio domicilio
                  </button>
                @endif
              </td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_system])
            </td>
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_system->appends(Input::only(['user_system']) + ['active_tab' => 'user_system'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif