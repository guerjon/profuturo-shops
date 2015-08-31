@extends('layouts.master')

@section('content')
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
      'action' => 'AdminFurnitureImporterController@store',
      'role' => 'form',
      'files' => true
      ])}}

      <fieldset>
        <legend>
          Importar un archivo Excel con información de mobiliario
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
