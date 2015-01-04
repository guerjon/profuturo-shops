@extends('layouts.master')

@section('content')


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

      <div class="form-group">
        {{Form::label('email', 'Correo electrónico')}}
        {{Form::email('email', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('password', 'Contraseña')}}
        {{Form::password('password', ['class' => 'form-control'])}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Enviar', ['class' => 'btn btn-primary'])}}
      </div>
    {{Form::close()}}
  </div>
</div>
@stop
