@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">
    <hr>
    <h2>Añadir nueva subcagetoría para <strong>{{$category->name}}</strong></h2>
    {{Form::model($subcategory, [
      'action' => $subcategory->exists ? ['AdminFurnitureSubcategoriesController@update', $subcategory->id] : 'AdminFurnitureSubcategoriesController@store',
      'method' => $subcategory->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}

      {{Form::hidden('furniture_category_id',$category->id)}}
      
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
