<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleado
      </th>
      <th>
        Cantidad
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
    </tr>
    @endif
  </tbody>

</table>

<div class="well">
  Pedido recibido el día {{$bc_order->updated_at->format('d-m-Y')}}
</div>
