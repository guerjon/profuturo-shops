@include('admin::users.partials.filters')

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
            <th>Número de empleado</th>
            <th>Línea de negocio</th>
            <th>Extensión</th>
            <th>Domicilio</th>
            <th></th>
        
          </tr>
        </thead>

        <tbody>
          @foreach ($users_paper as $user_paper)
            <tr class="{{(Session::get('focus') == $user_paper->id) ? 'info' : ''}}">
               <td>{{$user_paper->ccosto}}</td>
               <td>{{$user_paper->gerencia}}</td>
               <td>{{$user_paper->region ? $user_paper->region->name : 'N/A'}}</td>
               <td>{{$user_paper->divisional ? $user_paper->divisional->id : 'N/A' }}</td>
               <td>{{$user_paper->num_empleado}}</td>
               <td>{{$user_paper->linea_negocio}}</td>  
               <td>{{$user_paper->extension}}</td>
               <td>{{$user_paper->address ? $user_paper->address->domicilio : 'N/A'}}
                  @if($user_paper->address ? ($user_paper->address->posible_cambio != null) : false )
                    <button data-id="{{$user_paper->address->id}}" data-domicilio="{{$user_paper->address->domicilio}}" data-posible-cambio="{{$user_paper->address->posible_cambio}}" class="btn btn-primary btn-xs" id="cambio">
                      Ver cambio domicilio
                    </button>
                  @endif
               </td>
              <td>
                @include('admin::users.partials.actions', ['user' => $user_paper])
              </td>
     
             </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
  <div class="text-center">
    {{ $users_paper->appends(Input::only(['user_paper']) + ['active_tab' => 'user_paper'])->links()}}
  </div>

  
@else
  <div class="alert alert-info">
    No hay usuarios que mostrar
  </div>
@endif



