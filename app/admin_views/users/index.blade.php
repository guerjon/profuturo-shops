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
<hr>
  <div class="" style="margin: 20px inherit">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="{{$active_tab == 'admin' ? 'active' : ''}}">
        <a href="?active_tab=admin&page=1" aria-controls="admin" class="tabs">
          Administradores
        </a>
      </li>
      <li role="presentation" class="{{$active_tab == 'manager' ? 'active' : ''}}">
        <a href="?active_tab=manager&page=1" aria-controls="manager" class="tabs">
          Consultores
        </a>
      </li>
      <li role="presentation" class="{{$active_tab == 'user_requests' ? 'active' : ''}}">
        <a href="?active_tab=user_requests&page=1" aria-controls="user_requests" class="tabs">
          Usuarios proyectos
        </a>
      </li>
      <li role="presentation" class="{{$active_tab == 'user_paper' ? 'active' : ''}}">
        <a href="?active_tab=user_paper&page=1" aria-controls="user_paper" class="tabs">
          Usuarios papeleria
        </a>
      </li>      
    </ul>
  </div>

  <div class="tab-content" id="busqueda-plantilla">
    <div role="tabpanel" class="tab-pane {{$active_tab == 'admin' ? 'active' : ''}}" id="admin">
      @include('admin::users.partials.admins')
    </div>
    <div role="tabpanel" class="tab-pane {{$active_tab == 'manager' ? 'active' : ''}}" id="manager">
      @include('admin::users.partials.managers')
    </div>
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_requests' ? 'active' : ''}}" id="user_requests">
      @include('admin::users.partials.users_requests')
    </div>
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_paper' ? 'active' : ''}}" id="user_paper">
      @include('admin::users.partials.users_paper')
    </div>    
  </div>



<div class="text-right">
  {{link_to_action('AdminApiController@getUsersReport', 'Descargar reporte', [], ['class' => 'btn btn-warning'])}}
  {{link_to_action('AdminUsersController@create', 'Crear nuevo usuario', [], ['class' => 'btn btn-warning'])}}
</div>



@stop
