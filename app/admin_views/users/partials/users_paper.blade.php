{{ Form::open([
  'method' => 'GET',
  'class' => 'form-horizontal'
  ]) }}
  {{ Form::hidden('active_tab', 'user_paper') }}
  <div class="form-group">
    <label for="user-paper-employee-number" class="control-label col-xs-2">CCOSTO</label>
    <div class="col-xs-2">
      {{Form::text('user_paper[employee_number]', (Input::get('user_paper')['employee_number']), ['class' => 'form-control', 'maxlength' => 6])}}
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
      @foreach ($users_paper as $user_paper)
        <tr class="{{(Session::get('focus') == $user_paper->id) ? 'info' : ''}}">
           <td>{{$user_paper->ccosto}}</td>
           <td>{{$user_paper->gerencia}}</td>
           <td>{{$user_paper->region ? $user_paper->region->name : 'N/A'}}</td>
           <td>{{$user_paper->divisional}}</td>
           <td>{{$user_paper->linea_negocio}}</td>
           <td>{{$user_paper->email}}</td>
           <td>{{$user_paper->extension}}</td>
          <td>
            @include('admin::users.partials.actions', ['user' => $user_paper])
          </td>
           <td>
            {{HTML::image($user_paper->image->url('mini'),$user_paper->nombre, ['class' => 'img-rounded','style' => 'height: 30px;width: 30px;'] )}}        
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
