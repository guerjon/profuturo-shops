@extends('layouts.master')

@section('content')

<h3>Detalles del pedido {{$bc_order->id}}<br><small>{{$bc_order->comments}}</small></h3>

@if($bc_order->status == 1)
  @include('corporation_bc_orders.partials.received_show')
@else
  @include('corporation_bc_orders.partials.receive_form')
@endif
@stop
