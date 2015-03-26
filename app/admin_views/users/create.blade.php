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
        {{Form::label('ccosto', 'Centro de costos')}}
        {{Form::number('ccosto', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('gerencia', 'Nombre/Gerencia')}}
        {{Form::text('gerencia', NULL, ['class' => 'form-control'])}}
      </div>


      <div class="form-group">
        {{Form::label('password', 'Contraseña')}}
        {{Form::password('password', ['class' => 'form-control'])}}
      </div>


      <div class="form-group">
        {{Form::label('role', 'Perfil')}}
        <br>
        <label class="radio-inline">{{Form::radio('role', 'admin')}} Administrador</label>
        <label  class="radio-inline">{{Form::radio('role', 'manager')}} Asesor</label>
        <br>
        <label class="radio-inline">{{Form::radio('role', 'user_requests')}} Usuario proyectos</label>
        <label  class="radio-inline">{{Form::radio('role', 'user_paper')}} Usuario papelería</label>

      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
      </div>
    {{Form::close()}}
  </div>

</div>
@stop
