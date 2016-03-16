@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Pedidos Mobiliario</li>
  </ol>

@if($orders->count() == 0)
<div class="alert alert-info">
  No se han realizado pedidos todavía.
</div>

@else

  {{Form::open([
    'method' => 'GET',
    'class' => 'form-inline'
    ])}}

    <div class="form-group">
      {{Form::number('ccosto', Input::get('ccosto'), ['class' => 'form-control', 'placeholder' => 'CCOSTOS'])}}
    </div>

    <div class="form-group">
      {{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary">
        <span class="glyphicon glyphicon-filter"></span> Filtrar
      </button>
    </div>

  {{Form::close()}}
  
<a href="{{action('AdminFurnituresOrdersController@index', ['export'=>'xls'])}}" class="btn btn-primary btn-submit" style="float:right">
  <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
</a>
  
<div class="container-fluid">
  <table class="table table-striped">
    <thead>
      <tr>

         <th>
        Gerencia
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
        <th>
         Acciones
        </th>
        
      </tr>
    </thead>

    <tbody>
      @foreach($orders as $order)
      <tr>
      <td>
      {{$order->user->gerencia}}
      </td>
        <td>
          {{$order->user->ccosto}}
        </td>
        <td>
          {{link_to_action('AdminFurnituresOrdersController@show', $order->id, [$order->id])}}
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
     {{Form::open(array('action' =>['AdminFurnituresOrdersController@destroy',$order->id],
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
</div>

@endif
@stop
