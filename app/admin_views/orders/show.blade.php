@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="#" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="admin/orders">Pedidos Papelería</a></li>
    <li class="active">Detalles</li>
  </ol>

<h3>Detalles del pedido {{$order->id}}<br><small>{{$order->comments}}</small></h3>

<h5>Pedido de: {{$order->user->first_name}} {{$order->user->last_name}}</h5>
<div class="container">
<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Producto
      </th>
      <th>
        Cantidad
      </th>
      <th>
        Estado
      </th>
      <th>
        Comentarios
      </th>
       <th>
       Eliminar
      </th>
    </tr>
  </thead>

  <tbody>
    <? $total = 0 ?>
    @foreach($order->products as $product)
    <tr>
      <td>
        {{$product->name}}
      </td>

      <td class="product-quantity">
        {{$product->pivot->quantity}}
      </td>

      <td>
        {{$product->pivot->status ? 'Completo' : 'Incompleto'}}
      </td>
      <td>
        {{$product->pivot->status ? '' : $product->pivot->comments}}
      </td>
      <td>
        <button class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="1">Eliminar 1</button>
        <button class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="{{$product->pivot->quantity}}">Eliminar todos</button>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
</div>


@if($order->status == 1)
<p class="well">
  {{$order->receive_comments}}
  Pedido recibido el día {{$order->updated_at->format('d-m-Y')}}
</p>
@elseif($order->status == 2 and $complain = $order->order_complain)
<div class="alert alert-danger">
  {{$complain->complain}}
</div>

@endif

@stop
