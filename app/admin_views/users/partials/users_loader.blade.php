{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_loader') }}
  <div class="form-group">
    <label for="user-loader-employee-number" class="control-label col-xs-2">Centro de costos</label>
    <div class="col-xs-2">
      {{Form::text('user_loader[employee_number]', (Input::get('user_loader')['employee_number']), ['class' => 'form-control'])}}
    </div>
    <label for="user-loader-employee-number" class="control-label col-xs-2">Gerencia</label>
    <div class="col-xs-2">
      {{Form::text('user_loader[gerencia]', (Input::get('user_loader')['gerencia']), ['class' => 'form-control'])}}
    </div>

{{--     <div class="col-xs-3">
      {{ Form::select('executive[management_id]', $managements,
        Input::get('executive')['management_id'], ['class' => 'form-control']) }}
    </div> --}}
    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}




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
