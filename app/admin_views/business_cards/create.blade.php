@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/business-cards">Tarjetas de Presentación</a></li>
  <li class="active">Importar Excel</li>
</ol>

@if($errors->count())
@foreach($errors->all() as $error)
<div class="alert alert-danger">
{{$error}}
</div>
@endforeach

@endif

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    {{Form::open([
      'action' => 'AdminBusinessCardsController@store',
      'role' => 'form',
      'files' => true
      ])}}

      <fieldset>
        <legend>
          Importar un archivo Excel con información de tarjetas de presentación
        </legend>
        <div class="form-group">
          {{Form::label('file', 'Archivo Excel')}}
          {{Form::file('avatar')}}
        </div>
          <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

        <div class="form-group text-center">
          {{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
        </div>
      </fieldset>
    {{Form::close()}}

  </div>
</div>


@stop
