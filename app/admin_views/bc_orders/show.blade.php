@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="/admin/bc-orders">Pedidos de Tarjetas</a></li>
    <li class="active">Detalles</li>
  </ol>

<h3>Detalles</h3>

<div class="container-fluid">
<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleado
      </th>
      <th>
        Inmueble
      </th>
      <th>
        Estatus
      </th>
      <th>
        Comentarios
      </th>
      <th>
        Dirección
      </th>
      <th>
        Dirección alternativa
      </th>
      <th>
        Teléfono
      </th>
      <th>
        Celular
      </th>
      <th>
        Email
      </th>
      <th>
        Puesto inglés
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach($bc_order->business_cards as $card)
    <tr>
      <td>
        {{$card->nombre}}
      </td>
      <td>
        {{$card->pivot->inmueble}}
      </td>
      <td>
        @if($bc_order->status == 0)
          Pendiente
        @elseif($bc_order->status==1)
          Recibido <span class="glyphicon glyphicon-check"></span>
        @elseif($bc_order->status==2)
          Recibido incompleto.
        @endif
      </td>
      <td>
        {{$card->pivot->status ? '' : $card->pivot->comments}}
      </td>
      <td>
        {{$card->pivot->direccion ? $card->pivot->direccion : $card->direccion}}
      </td>
      <td>
        {{$card->pivot->direccion_alternativa_tarjetas ? $card->pivot->direccion_alternativa_tarjetas : $card->direccion_alternativa }}
      </td>
      <td>
        {{$card->pivot->telefono ? $card->pivot->telefono : $card->telefono}}
      </td>
      <td>
        {{$card->pivot->celular ? $card->pivot->celular : $card->celular}}
      </td>
      <td>
        {{$card->pivot->email ? $card->pivot->email : $card->email}}
      </td>
      <td>
        {{$card->pivot->position ? $card->pivot->position : $card->position}}
      </td>
    </tr>
    @endforeach
    @if($blank_card)
      <tr>
        <td>
          Tarjetas blancas
        </td>
        <td>
          {{$blank_card->quantity}}
        </td>

        <td>
          @if($bc_order->status == 0)
            Pendiente
          @elseif($bc_order->status==1)
            Recibido <span class="glyphicon glyphicon-check"></span>
          @elseif($bc_order->status==2)
            Recibido incompleto.
          @endif
        </td>
        <td>
          {{$blank_card->status ? ' ' : $blank_card->comments}}
        </td>
        <td>
        </td>
        <td>
          {{$blank_card->direccion_alternativa_tarjetas}}
        </td>
        <td>
          {{$blank_card->telefono_tarjetas}}
        </td>
        <td></td>
        <td>
          {{$blank_card->email}}
        </td>
      </tr>
    @endif
    @if($bc_order->extra)
      <tr>
        <td>
          {{$bc_order->extra->talento_nombre}}
        </td>
        <td>
          100
        </td>
        <td>
          @if($bc_order->extra->talento_estatus == 1)
            Completo
          @else
            Incompleto
          @endif
        </td>
        <td>
          {{$bc_order->extra->talento_estatus ? '' : $bc_order->extra->talento_comentarios}}
        </td>
      </tr>
    <tr>
    <td>
      {{$bc_order->extra->gerente_nombre}}
    </td>
    <td>
      100
    </td>
    <td>
        @if($bc_order->status == 0)
          Pendiente
        @elseif($bc_order->status==1)
          Recibido <span class="glyphicon glyphicon-check"></span>
        @elseif($bc_order->status==2)
          Recibido incompleto.
        @endif
    </td>
      <td>
       {{$bc_order->extra->gerente_estatus ? '' : $bc_order->extra->gerente_comentarios}}
      </td>
    </tr>
    @endif

  </tbody>

</table>
</div>

@if($bc_order->status == 1)
<div class="well">
  Pedido recibido el día {{$bc_order->updated_at->format('d-m-Y')}}
</div>
@elseif($bc_order->status == 2)
<div class="alert alert-warning">
  {{$bc_order->receive_comments}}
</div>
@endif

@stop
