@extends('layouts.master')

@section('content')

<div class="row">
  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="{{action('AdminFurnitureCategoriesController@index')}}">Categorías</a></li>
    <li class="active">Crear Subcategoría</li>
  </ol>
  <div class="col-sm-8 col-sm-offset-2">
    
    {{Form::model($subcategory, [
      'action' => $subcategory->exists ? ['AdminFurnitureSubcategoriesController@update', $subcategory->id] : 'AdminFurnitureSubcategoriesController@store',
      'method' => $subcategory->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}
       @if($subcategory->exists)
          <h2>Editar subcagetoría</h2>
       @else
          <h2>Añadir nueva subcagetoría para {{$category->name}}</h2>
          {{Form::hidden('furniture_category_id',$category->id)}}
       @endif
       <hr>
      
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
