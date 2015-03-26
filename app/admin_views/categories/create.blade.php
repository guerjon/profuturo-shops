@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">
    {{Form::model($category, [
      'action' => $category->exists ? ['AdminCategoriesController@update', $category->id] : 'AdminCategoriesController@store',
      'method' => $category->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}

      <div class="form-group">
        {{Form::label('name', 'Nombre')}}

        {{Form::text('name', NULL, ['class' => 'form-control'])}}
      </div>

      <div class='form-group'>
        {{Form::label('image', 'Imagen')}}
        {{Form::file('image')}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg'])}}
      </div>

    {{Form::close()}}

</div>

@stop
