@extends('layouts.master')

@section('content')

@if($bc_orders->count() == 0)
<div class="alert alert-info">
  No hay pedidos de tarjetas de presentación
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
    @foreach($bc_orders as $order)
    <tr>
      <td>
        {{$order->user->gerencia}}
      </td>
      <td>
        {{link_to_action('AdminBcOrdersController@show', $order->id, [$order->id])}}
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
        @endif
      </td>
    </tr>


    @endforeach
  </tbody>
</table>

@endif
@stop
