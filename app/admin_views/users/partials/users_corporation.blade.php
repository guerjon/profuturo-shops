@include('admin::users.partials.filters')


@if($users_corporation->count() > 0)

<div class="container-fluid">
  <div class="table-responsive">

    <table class="table table-striped">

      <thead>
        <tr>
          <th>C. Costos</th>
          <th>Gerencia</th>
          <th>Región</th>
          <th>Extensión</th>
          <th>Número de empleado</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($users_corporation as $user_corporation)
          <tr class="{{(Session::get('focus') == $user_corporation->id) ? 'info' : ''}}">
             <td>{{$user_corporation->ccosto}}</td>
             <td>{{$user_corporation->gerencia}}</td>
             <td>{{$user_corporation->region ? $user_corporation->region->name : 'N/A'}}</td>
             <td>{{$user_corporation->extension}}</td>
             <td>{{$user_corporation->num_empleado}}</td>
            <td>
              @include('admin::users.partials.actions', ['user' => $user_corporation])
            </td>
           </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="text-center">
  {{ $users_corporation->appends(Input::only(['user_corporation']) + ['active_tab' => 'user_corporation'])->links()}}
</div>
@else
<div class="alert alert-info">
  No hay usuarios que mostrar
</div>
@endif
