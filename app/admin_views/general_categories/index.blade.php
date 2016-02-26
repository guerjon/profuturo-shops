@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Categorías</li>
  </ol>

<h2 style="text-align:center">Categorías</h2>

<hr>

<div class="row">
    <div class="col-sm-2 col-xs-offset-3">
      <a href="{{action('AdminCategoriesController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Categorías Productos</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminFurnitureCategoriesController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Categorías Mobiliario</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminMacCategoriesController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Categorías MAC</h3>
      </a>
    </div>  
</div>

@stop
