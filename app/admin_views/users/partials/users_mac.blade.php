{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_mac') }}
  <div class="form-group">
    <label for="user-paper-employee-number" class="control-label col-xs-2">Centro de costos</label>
    <div class="col-xs-2">
      {{Form::text('user_mac[employee_number]', (Input::get('user_mac')['employee_number']), ['class' => 'form-control'])}}
    </div>
    <label for="user-paper-employee-number" class="control-label col-xs-2">Gerencia</label>
    <div class="col-xs-2">
      {{Form::text('user_mac[gerencia]', (Input::get('user_mac')['gerencia']), ['class' => 'form-control'])}}
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




@if($users_paper->count() > 0)

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
        @foreach ($users_paper as $user_mac)
          <tr class="{{(Session::get('focus') == $user_mac->id) ? 'info' : ''}}">
             <td>{{$user_mac->ccosto}}</td>
             <td>{{$user_mac->gerencia}}</td>
             <td>{{$user_mac->region ? $user_mac->region->name : 'N/A'}}</td>
             <td>{{$user_mac->divisional ? $user_mac->divisional->id : 'N/A' }}</td>
             <td>{{$user_mac->linea_negocio}}</td>  
             <td>{{$user_mac->extension}}</td>
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
  {{ $users_paper->appends(Input::only(['user_mac']) + ['active_tab' => 'user_mac'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
