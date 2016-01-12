{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_furniture') }}
  <div class="form-group">
    <label for="user-paper-employee-number" class="control-label col-xs-2">Centro de costos</label>
    <div class="col-xs-2">
      {{Form::text('user_furniture[employee_number]', (Input::get('user_furniture')['employee_number']), ['class' => 'form-control'])}}
    </div>
   
    <label for="user-paper-employee-number" class="control-label col-xs-2">Gerencia</label>
      <div class="col-xs-2">
        {{Form::text('user_furniture[gerencia]', (Input::get('user_furniture')['gerencia']), ['class' => 'form-control'])}}
      </div>

    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}




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
