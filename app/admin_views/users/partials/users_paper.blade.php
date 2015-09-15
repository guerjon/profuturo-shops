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




@if($users_paper->count() > 0)
<div class="table-responsive">

  <table class="table table-striped">

    <thead>
      <tr>
        <th>C. Costos</th>
        <th>Gerencia</th>
        <th>Region(s)</th>
        <th>Divisional</th>
        <th>Linea de negocio</th>
        <th>Email</th>
        <th>Extension</th>
        <th></th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      @foreach ($managers as $manager)
        <tr class="{{(Session::get('focus') == $manager->id) ? 'info' : ''}}">
           <td>{{$manager->ccosto}}</td>
           <td>{{$manager->gerencia}}</td>
           <td>{{$manager->region ? $manager->region->name : 'N/A'}}</td>
           <td>{{$manager->divisional}}</td>
           <td>{{$manager->linea_negocio}}</td>
           <td>{{$manager->email}}</td>
           <td>{{$manager->extension}}</td>
          <td>
            @include('admin::users.partials.actions', ['user' => $manager])
          </td>
           <td>
            {{HTML::image($manager->image->url('mini'),$manager->nombre, ['class' => 'img-rounded','style' => 'height: 30px;width: 30px;'] )}}        
           </td>
         


         </tr>
      @endforeach
    </tbody>
  </table>

</div>
<div class="text-center">
  {{ $users_paper->appends(Input::only(['user_paper']) + ['active_tab' => 'user_paper'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
