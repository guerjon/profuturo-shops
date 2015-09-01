@extends('layouts.master')

@section('content')

<h2>Reportes</h2>

<div class="row">

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getOrdersReport', 'Pedidos papelería', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getBcOrdersReport', 'Pedidos tarjetas', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getFurnituresOrdersReport', 'Reportes de mobiliario', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getUserOrdersReport', 'Usuarios sin pedidos', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getActiveUserOrdersReport', 'Usuarios con mayores pedidos', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getProductOrdersReport', 'Productos más solicitados', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getTotalUserOrdersReport', 'Pedidos totales de usuarios', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>
  
  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getGeneralRequestReport', 'Reportes de solicitudes generales', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>

  <div class="col-sm-4 col-xs-6">
    {{link_to_action('AdminReportsController@getBIReport', 'Reportes BI', [], ['class' => 'btn btn-lg btn-default btn-block'])}}
  </div>


</div>


@stop
