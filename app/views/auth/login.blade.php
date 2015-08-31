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
      'action' => 'AuthController@postLogin',
      'role' => 'form'
      ])}}
      <div class="text-center">
        <img id="img-home" src="/img/home.png">
      </div>
      <div class="form-group">
        {{Form::label('ccosto', 'Centro de costos o número de empleado:')}}
        {{Form::text('ccosto', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('password', 'Contraseña:')}}
        {{Form::password('password', ['class' => 'form-control'])}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
      </div>

<!--       <div class="form-group text-right">
        <a href="/admin-login">Inicio de sesión administrativo</a>
      </div> -->
    {{Form::close()}}
  </div>
</div>
@stop
