{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'manager') }}
  <div class="form-group">
    <label for="manager-employee-number" class="control-label col-xs-2">CCOSTOS</label>
    <div class="col-xs-2">
      {{Form::text('manager[employee_number]', (Input::get('manager')['employee_number']), ['class' => 'form-control', 'maxlength' => 6])}}
    </div>
{{--     <div class="col-xs-3">
      {{ Form::select('manager[management_id]', $managements,
        Input::get('manager')['management_id'], ['class' => 'form-control']) }}
    </div> --}}
    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
{{ Form::close() }}
@if($managers->count() > 0)
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
      @foreach ($managers as $manager)
        <tr class="{{(Session::get('focus') == $manager->id) ? 'info' : ''}}">
           <td>{{$manager->ccosto}}</td>
           <td>{{$manager->gerencia}}</td>
           <td>{{$manager->linea_negocio}}</td>
           <td>{{$manager->nombre}}</td>
           <td>{{$manager->num_empleado}}</td>
           <td>{{$manager->email}}</td>
           <td>{{$manager->extension}}</td>
           <td>{{$manager->celular}}</td>
          <td>
            @include('admin::users.partials.actions', ['user' => $manager])
          </td>
         


         </tr>
      @endforeach
    </tbody>
  </table>

</div>
<div class="text-center">
  {{ $managers->appends(Input::only(['manager']) + ['active_tab' => 'manager'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
