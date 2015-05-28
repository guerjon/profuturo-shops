@extends('layouts.master')

@section('content')

@if($errors->count() > 0)
<div class="alert alert-danger">
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="text-right">
  {{link_to_action('AdminUsersController@getImport', 'Importar Excel', [], ['class' => 'btn btn-warning'])}}
  {{link_to_action('AdminApiController@getUsersReport', 'Descargar reporte', [], ['class' => 'btn btn-warning'])}}
  <!--{{link_to_action('AdminUsersController@create', 'Crear nuevo usuario', [], ['class' => 'btn btn-warning'])}} -->

</div>


<h3>Centros de costos</h3>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Clave
        </th>
        <th>
          Centro de costos
        </th>
          <th>
          Linea de negocios
        </th>
      </tr>
    </thead>

    <tbody>
      @foreach($users as $user)
      <tr>
        <td>
          {{$user->ccosto}}
          <span class="label label-default">{{$user->role == 'user_requests' ? 'Proyectos' : 'Papelería'}}</span>
        </td>
        <td>
          {{$user->gerencia}}
        </td>
        <td>
          {{$user->linea_negocio}}
        </td>
        <td>
          @include('admin::users.partials.actions')
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<h4>Administración</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Nombre
        </th>
        <th>

        </th>
      </tr>
    </thead>

    <tbody>
      @foreach($admins as $user)
      <tr>
        <td>
          {{$user->gerencia}}
          @if($user->is_admin)
          <span class="label label-info">Admin</span>
          @endif
        </td>
        <td>
          {{$user->first_name}} {{$user->last_name}}
        </td>
        <td>
          @include('admin::users.partials.actions')
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="text-right">
  {{link_to_action('AdminApiController@getUsersReport', 'Descargar reporte', [], ['class' => 'btn btn-warning'])}}
  {{link_to_action('AdminUsersController@create', 'Crear nuevo usuario', [], ['class' => 'btn btn-warning'])}}
</div>



@stop
