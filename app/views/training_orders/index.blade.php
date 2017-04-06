@extends('layouts.master')

@section('content')

@if($orders->count() == 0)
<div class="alert alert-info">
  Usted no ha realizado pedidos todavía.
  @if(Auth::user()->cart_products->count() > 0)
  Haga click <a href="/carrito-training" class="alert-link">aquí</a> para revisar su carrito y enviar su orden.
  @else
  Haga click <a href="/training-products" class="alert-link">aquí</a> para agregar productos a su carrito.
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
        Sede
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
        Orden extra
      </th>
      <th>
        
      </th>
    
    </tr>
  </thead>

  <tbody>
    @foreach($orders as $order)
    <tr>
      <td>
        {{link_to_action('TrainingOrdersController@show', $order->id, [$order->id])}}
      </td>
      <td>
        {{$order->sede ? $order->sede->name : null}}
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
      <td>
        @if($order->extra_order)
          <div>
            Orden extra  
            <span class="glyphicon glyphicon-ok" style="color:green"></span>
          </div>
        @endif
      </td>
      @if($order->status == 0)
      <td>
    {{Form::open(array('action' =>['TrainingOrdersController@destroy',$order->id],'method' => 'delete'))}}
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
