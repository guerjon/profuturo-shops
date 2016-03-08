{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'admin') }}
  <div class="form-group">
    <div class="col-xs-2">
      {{Form::text('admin[employee_number]', (Input::get('admin')['employee_number']), ['placeholder' => 'Número de Empleado','class' => 'form-control'])}}
    </div>
    <div class="col-xs-2">
      {{Form::text('admin[gerencia]',(Input::get('admin')['gerencia']),['placeholder' => 'Gerencia','class' => 'form-control'])}}
    </div>

    <div class="col-xs-1">
      <button type="submit" class="btn btn-block btn-default">
        <span class="glyphicon glyphicon-search"></span> 
      </button>
    </div>
  </div>
{{ Form::close() }}


@if($admins->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C.Costos</th>
          <th>Gerencia</th>
          <th>Linea de negocio</th>
          <th>Email</th>

          <th></th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($admins as $admin)
          <tr class="{{(Session::get('focus') == $admin->id) ? 'info' : ''}}">
             <td>{{$admin->ccosto}}</td>
             <td style="width: 13%">{{$admin->gerencia}}</td>
             <td>{{$admin->linea_negocio}}</td>
             <td>{{$admin->email}}</td>

            <td>
              @include('admin::users.partials.actions', ['user' => $admin])
            </td>
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $admins->appends(Input::only(['admin']) + ['active_tab' => 'admin'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
