{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'admin') }}
  <div class="form-group">
    <label for="admin-ccostos" class="control-label col-xs-2">Centro de costos</label>
    <div class="col-xs-2">
      {{Form::text('admin[employee_number]', (Input::get('admin')['employee_number']), ['class' => 'form-control', 'maxlength' => 6])}}
    </div>
    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span> 
      </button>
    </div>
  </div>
{{ Form::close() }}
@if($admins->count() > 0)
<div class="table-responsive">

  <table class="table table-striped">

    <thead>
      <tr>
        <th>CCOSTOS</th>
        <th>GERENCIA</th>
        <th>REGION(s)</th>
        <th>DIVISIONAL</th>
        <th>LINEA DE NEGOCIO</th>
        <th>NOMBRE</th>
        <th>NUMERO DE EMPLEADO</th>
        <th>EMAIL</th>
        <th>EXTENSION</th>
        <th>CELULAR</th>
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
  {{ $admins->appends(Input::only(['admin']) + ['active_tab' => 'admin'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
