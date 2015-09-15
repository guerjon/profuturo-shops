@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="#" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="#">Inicio</a></li>
    <li class="active">Pedidos de Tarjetas</li>
  </ol>

@if($bc_orders->count() == 0)
<div class="alert alert-info">
  No hay pedidos de tarjetas de presentación
</div>

@else

<a href="{{action('AdminBcOrdersController@index', ['export'=>'xls'])}}" class="btn btn-primary btn-submit" style="float:right">
  <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
</a>


<table class="table table-striped">
  <thead>
    <tr>

      <th>
        Clave CC
      </th>
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
        {{$order->user->ccosto}}
      </td>
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
       <td>
   {{Form::open(array('action' =>['AdminBcOrdersController@destroy',$order->id],
   'method' => 'delete'))}}

    <button type="submit" class="btn btn-danger btn-xs">
     <span class="glyphicon glyphicon-remove"></span> Eliminar
    </button>
     {{Form::close()}}
     </td>
    </tr>


    @endforeach
  </tbody>
</table>

@endif
@stop
