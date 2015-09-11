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

@unless($users_paper->count() == 0)
<div>
  <button type="button" class="btn btn-sm btn-primary" id="btn-multiple-assign">Asignación Múltiple</button>
  <div id="multiple-assign-btn-group" style="display:none">
    <button type="button" class="btn btn-sm btn-danger" id="btn-multiple-assign-cancel">Cancelar Asignación Múltiple</button>
    <button type="button" class="btn btn-sm btn-primary" id="btn-multiple-assign-assign">Asignar</button>
  </div>
</div>
<br>
@endunless


@if($users_paper->count() > 0)
<div class="table-responsive" id="user_paper">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. empleado</th>
        <th>Nombre(s)</th>
        <th>Apellido(s)</th>
        <th>Usuario CUSP</th>
        <th>Gerencia</th>
         <th>Gerente</th>
        <th>

        </th>
      </tr>
    </thead>

    <tbody>
      @foreach ($users_paper as $executive)

        @if($focus = Session::get('focus'))
          <?php $class = ((is_array($focus) and in_array($executive->id, $focus)) or ($focus == $executive->id)) ? 'info' : ''; ?>
        @else
          <?php $class = '' ?>
        @endif
        <tr class="{{$class}}">
           <td>
             <input class="executive-checkbox" type="checkbox" name="assign-executive[]" data-user-id="{{$executive->id}}" style="display:none">
             {{$executive->employee_number}}
           </td>
           <td>{{$executive->first_name}}</td>
           <td>{{$executive->last_name}}</td>
           <td>{{$executive->cusp}}</td>
           <td>{{$executive->management->name or 'N/A'}}</td>
           <td>{{$executive->manager->fullname or 'No asignado'}}</td>
          <td>
            @include('admin::users.partials.actions', ['user' => $executive])
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
