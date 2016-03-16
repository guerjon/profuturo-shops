{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_mac') }}
  <div class="form-group">
  
    <div class="col-xs-2">
      {{Form::text('user_mac[employee_number]', (Input::get('user_mac')['employee_number']), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
    </div>
    <div class="col-xs-2">
      {{Form::text('user_mac[gerencia]', (Input::get('user_mac')['gerencia']), ['placeholder' => 'Gerencia','class' => 'form-control'])}}
    </div>

    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}


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
          <th></th>
      
        </tr>
      </thead>

      <tbody>
        @foreach ($users_mac as $user_mac)
          <tr class="{{(Session::get('focus') == $user_mac->id) ? 'info' : ''}}">
             <td>{{$user_mac->ccosto}}</td>
             <td>{{$user_mac->gerencia}}</td>
             <td>{{$user_mac->region ? $user_mac->region->name : 'N/A'}}</td>
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
