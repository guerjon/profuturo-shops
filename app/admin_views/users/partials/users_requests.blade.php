{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'executive') }}
  <div class="form-group">
    <label for="executive-employee-number" class="control-label col-xs-2">Número de empleado</label>
    <div class="col-xs-2">
      {{Form::text('executive[employee_number]', (Input::get('executive')['employee_number']), ['class' => 'form-control', 'maxlength' => 6])}}
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


@if($users_requests->count() > 0)
<div class="table-responsive">

  <table class="table table-striped">

    <thead>
      <tr>
        <th>C. Costos</th>
        <th>Gerencia</th>
        <th>Region(s)</th>
        <th>Divisional</th>
        <th>Linea de negocio</th>
        <th>Nombre</th>
        <th>No. empleado</th>
        <th>Email</th>
        <th>Extension</th>
        <th>Celular</th>
        <th></th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      @foreach ($admins as $admin)
        <tr class="{{(Session::get('focus') == $admin->id) ? 'info' : ''}}">
           <td>{{$admin->ccosto}}</td>
           <td>{{$admin->gerencia}}</td>
           <td>{{$admin->region ? $admin->region->name : 'N/A'}}</td>
           <td>{{$admin->divisional}}</td>
           <td>{{$admin->linea_negocio}}</td>
           <td>{{$admin->nombre}}</td>
           <td>{{$admin->num_empleado}}</td>
           <td>{{$admin->email}}</td>
           <td>{{$admin->extension}}</td>
           <td>{{$admin->celular}}</td>
          <td>
            @include('admin::users.partials.actions', ['user' => $admin])
          </td>
           <td>
            {{HTML::image($admin->image->url('mini'),$admin->nombre, ['class' => 'img-rounded','style' => 'height: 30px;width: 30px;'] )}}        
           </td>
         


         </tr>
      @endforeach
    </tbody>
  </table>

</div>
<div class="text-center">
  {{ $users_requests->appends(Input::only(['users_requests']) + ['active_tab' => 'users_requests'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
