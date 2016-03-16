{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_requests') }}
  
  <div class="form-group">
    <label for="user-request-employee-number" class="control-label"></label>
    <div class="col-xs-2">
      {{Form::text('user_requests[employee_number]', (Input::get('user_requests')['employee_number']), ['placeholder' => 'Número de Empleado', 'class' => 'form-control'])}}
    </div>
    
    <div class="col-xs-2">
      {{Form::text('user_requests[gerencia]', (Input::get('user_requests')['gerencia']), ['placeholder' => 'Gerencia', 'class' => 'form-control'])}}
    </div>
    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}


@if($users_requests->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C. Costos</th>
          <th>Gerencia</th>
          <th>Línea de negocio</th>
          <th>Nombre</th>
          <th>No. empleado</th>
          <th>Email</th>
          <th>Extensión</th>
          <th>Celular</th>
          <th></th>

        </tr>
      </thead>

      <tbody>
        @foreach ($users_requests as $user_requests)
            <tr class="{{(Session::get('focus') == $user_requests->id) ? 'info' : ''}}">
             <td>{{$user_requests->ccosto}}</td>
             <td>{{$user_requests->gerencia}}</td>
             <td>{{$user_requests->linea_negocio}}</td>
             <td>{{$user_requests->nombre}}</td>
             <td>{{$user_requests->num_empleado}}</td>
             <td>{{$user_requests->email}}</td>
             <td>{{$user_requests->extension}}</td>
             <td>{{$user_requests->celular}}</td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_requests])
            </td>

           


           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_requests->appends(Input::only(['users_requests']) + ['active_tab' => 'user_requests'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
