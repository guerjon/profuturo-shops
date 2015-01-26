@extends('layouts.master')

@section('content')


<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleado
      </th>
      <th>
        Cantidad
      </th>
      <th>
        Estatus
      </th>
      <th>
        Comentarios
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
        {{$card->pivot->quantity}}
      </td>
      <td>
        {{$card->pivot->status ? 'Completo' : 'Incompleto'}}
      </td>
      <td>
        {{$card->pivot->status ? '' : $card->pivot->comments}}
      </td>
    </tr>
    @endforeach
  </tbody>

</table>

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
