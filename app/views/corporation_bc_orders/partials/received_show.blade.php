<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleado
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach($bc_order->business_cards as $card)
    <tr>
      <td>
        {{$card->nombre}}
      </td>
    </tr>
    @endforeach
    @if($blank_card)
    <tr>
      <td>
        Tarjetas blancas
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
    </tr>
    
    <tr>
    <td>
      {{$bc_order->extra->gerente_nombre}}
    </td>
    <td>
      100
    </td>
    </tr>
    @endif

  </tbody>

</table>

<div class="well">
  Pedido recibido el día {{$bc_order->updated_at->format('d-m-Y')}}
</div>
