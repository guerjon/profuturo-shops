@extends('layouts.master')

@section('content')
  
  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="{{action('AdminFurnitureCategoriesController@index')}}">Categorías</a></li>
    <li class="active">Categorías Corporativo</li>
  </ol>

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">
    {{Form::model($category, [
      'action' => $category->exists ? ['AdminFurnitureCategoriesController@update', $category->id] : 'AdminFurnitureCategoriesController@store',
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
        <a class="btn btn-danger btn-lg" href="{{action('AdminFurnitureCategoriesController@index')}}">Cancelar</a>
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg'])}}
      </div>

    {{Form::close()}}

</div>

@stop
