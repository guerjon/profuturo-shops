@extends('layouts.master')

@section('content')


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
          {{Form::file('file')}}
        </div>

        <div class="form-group text-center">
          {{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
        </div>
      </fieldset>
    {{Form::close()}}

  </div>
</div>


@stop
