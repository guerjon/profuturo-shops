@extends('layouts.master')

@section('content')

@if($orders->count() == 0)
<div class="alert alert-info">
  Usted no ha realizado pedidos todavía.
  @if(Auth::user()->cart_products->count() > 0)
  Haga click <a href="/carrito" class="alert-link">aquí</a> para revisar su carrito y enviar su orden.
  @else
  Haga click <a href="/productos" class="alert-link">aquí</a> para agregar productos a su carrito.
  @endif
</div>

@else

@if($errors->count() > 0)
<div class="alert alert-danger">
  {{$errors->first()}}
</div>
@endif

<table class="table table-striped">
  <thead>
    <tr>
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
        {{link_to_action('OrderFurnituresController@show', $order->id, [$order->id])}}
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
      @if($order->status == 0)
      <td>
    {{Form::open(array('action' =>['OrderFurnituresController@destroy',$order->id],'method' => 'delete'))}}
     <button class="btn btn-xs btn-danger" data-product-id="{{$order->id}}" >Eliminar</button>  
     {{Form::close()}}
       </td>
    @endif 
      </tr>


    @endforeach
  </tbody>
</table>

@endif
@stop
