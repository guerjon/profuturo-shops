{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_corporation') }}
  <div class="form-group">
  
    <div class="col-xs-2">
      {{Form::text('user_corporation[employee_number]', (Input::get('user_corporation')['employee_number']), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
    </div>
    <div class="col-xs-2">
      {{Form::text('user_corporation[gerencia]', (Input::get('user_corporation')['gerencia']), ['placeholder' => 'Gerencia','class' => 'form-control'])}}
    </div>

    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}


@if($users_corporation->count() > 0)

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
        @foreach ($users_corporation as $user_corporation)
          <tr class="{{(Session::get('focus') == $user_corporation->id) ? 'info' : ''}}">
             <td>{{$user_corporation->ccosto}}</td>
             <td>{{$user_corporation->gerencia}}</td>
             <td>{{$user_corporation->region ? $user_corporation->region->name : 'N/A'}}</td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_corporation])
            </td>
   
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_corporation->appends(Input::only(['user_corporation']) + ['active_tab' => 'user_corporation'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
