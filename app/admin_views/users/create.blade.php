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
      'method' => $user->exists ? 'PUT' : 'POST'
      ])}}

      <div class="form-group">
        {{Form::label('first_name', 'Nombre(s)')}}
        {{Form::text('first_name', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('last_name', 'Apellido(s)')}}
        {{Form::text('last_name', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('email', 'Correo electrónico')}}
        {{Form::email('email', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('password', 'Contraseña')}}
        {{Form::password('password', ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('role', 'Perfil')}}
        <br>
        <label class="radio-inline">{{Form::radio('role', 'admin')}} Administrador</label>
        <label  class="radio-inline">{{Form::radio('role', 'user')}} Usuario</label>
      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-primary'])}}
      </div>
    {{Form::close()}}
  </div>

</div>
@stop
