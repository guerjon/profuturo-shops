@extends('layouts.master')

@section('content')

<h3>Detalles del pedido {{$order->id}}<br><small>{{$order->comments}}</small></h3>

@if($order->status == 1)
  @include('orders_furnitures.partials.received_show')
@else
  @include('orders_furnitures.partials.receive_form')
@endif
@stop
