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
  <div class="col-sm-2 col-xs-3 col-xs-offset-3">
    <a href="{{action('AdminReportsController@getActiveUserOrdersChangeReport')}}"class="thumbnail">
      <img src="/img/mayores_pedidos.png" style="width: 100%; height: auto;">
	  </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getUserOrdersReport')}}"class="thumbnail">
      <img src="/img/usuarios_pedidos.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getProductOrdersReport')}}"class="thumbnail">
      <img src="/img/mas_solicitados.png" style="width: 100%; height: auto;">
    </a>
  </div>
</div>
<div class="row"> 
  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getBIReport')}}"class="thumbnail">
      <img src="/img/reporte_bi.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getTotalUserOrdersReport')}}"class="thumbnail">
      <img src="/img/pedidos_totales.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getFurnituresOrdersReport')}}"class="thumbnail">
      <img src="/img/mobiliario.png" style="width: 100%; height: auto;">
    </a>
  </div>
</div>
<div class="row"> 

  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getOrdersReport')}}"class="thumbnail">
      <img src="/img/pedidos_papeleria.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getBcOrdersReport')}}"class="thumbnail">
      <img src="/img/pedidos_tarjetas.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3">
    <a href="{{action('AdminReportsController@getGeneralRequestReport')}}"class="thumbnail">
      <img src="/img/solicitudes_generales.png" style="width: 100%; height: auto;">
    </a>
  </div>
</div>
<div class="row">
  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getAllProductsReport')}}"class="thumbnail">
      <img src="/img/pedidos_estatus.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3 ">
    <a href="{{action('AdminReportsController@getMacOrdersReport')}}"class="thumbnail">
      <img src="/img/pedidos_mac.png" style="width: 100%; height: auto;">
    </a>
  </div>
  <div class="col-sm-2 col-xs-3 ">
    <a href="{{action('AdminReportsController@getCorporationOrdersReport')}}"class="thumbnail">
      <img src="/img/pedidos_corporativo.png" style="width: 100%; height: auto;">
    </a>
  </div>
</div>
<div class="row">
  <div class="col-sm-2 col-xs-3 col-xs-offset-3 ">
    <a href="{{action('AdminReportsController@getBiMacReport')}}"class="thumbnail">
      <img src="/img/reporte_mac.png" style="width: 100%; height: auto;">
    </a>
  </div>

  <div class="col-sm-2 col-xs-3 ">
    <a href="{{action('AdminReportsController@getBiCorporationReport')}}"class="thumbnail">
      <img src="/img/reporte_corporativo.png" style="width: 100%; height: auto;">
    </a>
  </div>

</div>
</div>


@stop
