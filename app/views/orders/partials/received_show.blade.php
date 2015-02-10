<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Producto
      </th>
      <th>
        Cantidad
      </th>

    </tr>
  </thead>

  <tbody>
    @foreach($order->products as $product)
    <tr>
      <td>
        {{$product->name}}
      </td>
      <td>
        {{$product->pivot->quantity}}
      </td>

    </tr>
    @endforeach
  </tbody>

</table>

<div class="well">
  {{$order->receive_comments}}
  <br>
  Pedido recibido el día {{$order->updated_at->format('d-m-Y')}}
</div>
