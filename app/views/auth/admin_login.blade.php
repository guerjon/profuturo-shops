@extends('layouts.master')

@section('content')
<style>
  #img-home{
    max-width: 100%;
    max-height: 350px;
    vertical-align:middle;
  }
</style>

<div class="row">

  <div class="col-sm-8 col-sm-offset-2">

    @if($errors->login->count() > 0)
      <div class="alert alert-danger">
        {{$errors->login->first()}}
      </div>
    @endif
    {{Form::open([
      'action' => 'AuthController@postAdminLogin',
      'role' => 'form'
      ])}}
      <div class="text-center">
        <img id="img-home" src="/img/home.png">
      </div>
      <div class="form-group">
        {{Form::label('email', 'Correo electrónico')}}
        {{Form::email('email', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('password', 'Contraseña')}}
        {{Form::password('password', ['class' => 'form-control'])}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
      </div>

      <div class="form-group text-right">
        <a href="/login">Iniciar sesión como usuario</a>
      </div>
    {{Form::close()}}
  </div>
</div>
@stop
