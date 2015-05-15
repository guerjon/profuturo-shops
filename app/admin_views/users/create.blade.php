@extends('layouts.master')

@section('content')


<div class="row">

  <div class="col-sm-8 col-sm-offset-2">

    @if($user->getErrors()->count() > 0)
      <div class="alert alert-danger">
        @if($user->getErrors()->count() == 1)
          {{$user->getErrors()->first()}}
        @else
          Ocurrieron errores al guardar el nuevo usuario:
          <ul>
            @foreach($user->getErrors()->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </ul>
        @endif
      </div>
    @endif
    {{Form::model($user, [
      'action' => $user->exists ? ['AdminUsersController@update', $user->id] : 'AdminUsersController@store',
      'method' => $user->exists ? 'PUT' : 'POST',
      'class' => 'form-horizontal',
      ])}}

      <div class="form-group">
        {{Form::label('ccosto', 'Centro de costos', ['class' => 'control-label col-sm-4'])}}
        <div class="col-sm-8">
          @if($user->exists)
          <p class="form-control-static">
            {{$user->ccosto}}
          </p>
          @else
          {{Form::number('ccosto', NULL, ['class' => 'form-control'])}}
          @endif
        </div>

      </div>

      <div class="form-group">
        {{Form::label('gerencia', 'Nombre/Gerencia', ['class' => 'control-label col-sm-4'])}}
        <div class="col-sm-8">
          {{Form::text('gerencia', NULL, ['class' => 'form-control'])}}
        </div>
      </div>

      <div class="form-group">
        {{Form::label('linea_negocio', 'Línea de negocio', ['class' => 'control-label col-sm-4'])}}
        <div class="col-sm-8">
          {{Form::text('linea_negocio', NULL, ['class' => 'form-control'])}}
        </div>
      </div>

      @unless($user->exists)
      <div class="form-group">
        {{Form::label('password', 'Contraseña', ['class' => 'control-label col-sm-4'])}}
        <div class="col-sm-8">
          {{Form::password('password', ['class' => 'form-control'])}}
        </div>

      </div>
      @endunless


      <div class="form-group">
        {{Form::label('role', 'Perfil', ['class' => 'control-label col-sm-4'])}}
        <div class="col-sm-8">
          @if($user->exists)
            <p class="form-control-static">
              @if($user->role == 'admin')
              Administrador
              @elseif($user->role == 'manager')
              Consultor
              @elseif($user->role == 'user_requests')
              Usuario proyectos
              @else
              Usuario papelería
              @endif
            </p>
          @else
            <label class="radio-inline">{{Form::radio('role', 'admin')}} Administrador</label>
            <label  class="radio-inline">{{Form::radio('role', 'manager')}} Consultor</label>
            <br>
            <label class="radio-inline">{{Form::radio('role', 'user_requests')}} Usuario proyectos</label>
            <label  class="radio-inline">{{Form::radio('role', 'user_paper')}} Usuario papelería</label>
          @endif
        </div>


      </div>

      <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4">
          {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
        </div>
      </div>
    {{Form::close()}}
  </div>

</div>
@stop
