@extends('layouts.master')

@section('content')

@if($orders->count() == 0)
<div class="alert alert-info">
  No se han realizado pedidos todavía.
</div>

@else

<table class="table table-striped">
  <thead>
    <tr>
      <th>
        Centro de costos
      </th>
      <th>
        No. pedido
      </th>

      <th>
        Comentarios
      </th>
      <th>
        Fecha de pedido
      </th>
      <th>
        Estatus
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach($orders as $order)
    <tr>
      <td>
        {{$order->user->ccosto}}
      </td>
      <td>
        {{link_to_action('AdminOrdersController@show', $order->id, [$order->id])}}
      </td>
      <td>
        {{$order->comments}}
      </td>
      <td>
        {{$order->created_at->format('d-m-Y')}}
      </td>
      <td>
        @if($order->status == 0)
        Pendiente
        @elseif($order->status==1)
        Recibido <span class="glyphicon glyphicon-check"></span>
        @elseif($order->status==2)
        Recibido incompleto.
        @if($complain = $order->order_complain)
        <small>{{$complain->complain}}</small>
        @endif
        @endif
      </td>
    </tr>


    @endforeach
  </tbody>
</table>

@endif
@stop
