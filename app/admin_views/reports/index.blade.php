@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Reportes</li>
  </ol>

<h2 style="text-align:center">Reportes</h2>

<hr>

<div class="row">
 <div class="clearfix"></div>
  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getActiveUserOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Mayores pedidos</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getUserOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Usuarios sin pedidos</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getProductOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Más solicitados</h3>
    </a>
  </div>
</div>
<div class="row"> 
  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getBIReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Reportes BI</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getTotalUserOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Pedidos totales</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getFurnituresOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Mobiliario</h3>
    </a>
  </div>
</div>
<div class="row"> 

  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Pedidos papelería</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getBcOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Pedidos tarjetas</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getGeneralRequestReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Sol. generales</h3>
    </a>
  </div>
</div>
<div class="row">
  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getAllProductsReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Pedidos por estado</h3>
    </a>
  </div>

  <div class="col-sm-2 col-xs-3 ">
    <a href="{{action('AdminReportsController@getMacOrdersReport')}}"class="thumbnail">
      <img src="/img/img.png" style="width: 100%; height: auto;">
        <h3 style="text-align:center">Reporte BI MAC</h3>
    </a>
  </div>
</div>
</div>


@stop
