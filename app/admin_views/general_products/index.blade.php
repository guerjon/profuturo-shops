@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Productos</li>
  </ol>

<h2>Productos</h2>

<hr>

<div class="row">
    <div class="col-sm-2 col-xs-offset-2">
      <a href="{{action('AdminProductsController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Productos papelería</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminBusinessCardsController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Productos Tarjetas</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminFurnituresController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Productos muebles</h3>
      </a>
    </div>  
    <div class="col-sm-2 ">
      <a href="{{action('AdminMacProductsController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Productos MAC</h3>
      </a>
    </div>
</div>

@stop
