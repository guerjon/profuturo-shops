@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Pedidos</li>
  </ol>

<h2>Pedidos</h2>

<hr>

<div class="row">
    <div class="col-sm-2 col-xs-offset-2">
      <a href="{{action('AdminOrdersController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Pedidos papelería</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminBcOrdersController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Pedidos Tarjetas</h3>
      </a>
    </div>

    <div class="col-sm-2">
      <a href="{{action('AdminFurnituresOrdersController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Pedidos muebles</h3>
      </a>
    </div>  
    <div class="col-sm-2 ">
      <a href="{{action('AdminMacOrdersController@index')}}"class="thumbnail">
        <img src="/img/img.png" style="width: 100%; height: auto;">
          <h3 style="text-align:center">Pedidos MAC</h3>
      </a>
    </div>
</div>

@stop
