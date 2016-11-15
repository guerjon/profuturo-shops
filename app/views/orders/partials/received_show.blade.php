<table class="table table-striped">

<div class="well">
  <ul>
    <li>
      Pedido recibido el día {{$order->updated_at->format('d-m-Y')}}    
    </li>
    <li>
      Dirección : {{$order->user ? ($order->user->address ? $order->user->address->domicilio : 'N/A') : 'N/A'}}
    </li>
    {{$order->receive_comments}}   
  </ul>
</div>


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

