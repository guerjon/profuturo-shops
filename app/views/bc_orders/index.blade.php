@extends('layouts.master')

@section('content')

@if($bc_orders->count() == 0)
<div class="alert alert-info">
  Usted no ha realizado pedidos todavía.
  Haga click <a href="/tarjetas-presentacion" class="alert-link">aquí</a> para hacer pedidos de tarjetas de presentación.
</div>

@else

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

      <th>
        
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach($bc_orders as $order)
    <tr>
      <td>
        {{link_to_action('BcOrdersController@show', $order->id, [$order->id])}}
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
       @if($order->status == 0)
      <td>
    {{Form::open(array('action' =>['BcOrdersController@destroy',$order->id],'method' => 'delete'))}}
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
