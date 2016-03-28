@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Usuarios</li>
  </ol>

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
  <a href="{{action('AdminUsersController@getImport')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-import"></span> Importar Excel
  </a>
  <a href="{{action('AdminApiController@getUsersReport')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-download-alt"></span> Descargar Reporte
  </a>
  <a href="{{action('AdminUsersController@create',['active_tab'=>$active_tab])}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Añadir usuario
  </a>
</div>

<h3>Usuarios</h3>
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
      <li role="presentation" class="{{$active_tab == 'user_paper' ? 'active' : ''}}">
        <a href="?active_tab=user_paper&page=1" aria-controls="user_paper" class="tabs">
          Papelería
        </a>
      </li>  
      <li role="presentation" class="{{$active_tab == 'user_requests' ? 'active' : ''}}">
        <a href="?active_tab=user_requests&page=1" aria-controls="user_requests" class="tabs">
          Proyectos
        </a>
      </li>   
      <li role="presentation" class="{{$active_tab == 'user_furnitures' ? 'active' : ''}}">
        <a href="?active_tab=user_furnitures&page=1" aria-controls="user_furnitures" class="tabs">
          Inmuebles
        </a>
      </li>
      <li role="presentation" class="{{$active_tab == 'user_loader' ? 'active' : ''}}">
        <a href="?active_tab=user_loader&page=1" aria-controls="user_loader" class="tabs">
          Cargador sucursal
        </a>
      </li>
      <li role="presentation" class="{{$active_tab == 'user_mac' ? 'active' : ''}}">
        <a href="?active_tab=user_mac&page=1" aria-controls="user_mac" class="tabs">
          MAC
        </a>
      </li>    
      <li role="presentation" class="{{$active_tab == 'user_corporation' ? 'active' : ''}}">
        <a href="?active_tab=user_corporation&page=1" aria-controls="user_corporation" class="tabs">
          Corporativo
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
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_furnitures' ? 'active' : ''}}" id="user_furnitures">
      @include('admin::users.partials.users_furnitures')
    </div> 
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_loader' ? 'active' : ''}}" id="user_loader">
      @include('admin::users.partials.users_loader')
    </div>   
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_mac' ? 'active' : ''}}" id="users_mac">
      @include('admin::users.partials.users_mac')
    </div> 
    <div role="tabpanel" class="tab-pane {{$active_tab == 'user_corporation' ? 'active' : ''}}" id="users_corporation">
      @include('admin::users.partials.users_corporation')
    </div> 
  </div>

  


@stop
