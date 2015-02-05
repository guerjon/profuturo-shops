{{Form::open([
  'action' => ['OrdersController@postReceive', $order->id],

  ])}}

<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Producto
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
    @foreach($order->products as $product)
    <tr>
      <td>
        {{$product->name}}
      </td>
      <td>
        {{$product->pivot->quantity}}
      </td>
      <td>
        {{Form::select("product[{$product->id}][status]",['Incompleto', 'Completo'], $product->pivot->status, ['class' => 'form-control'])}}
      </td>
      <td>
        {{Form::text("product[{$product->id}][comments]", $product->pivot->comments, ['class' => 'form-control']) }}
      </td>
    </tr>
    @endforeach

  </tbody>

</table>

<div class="form-group">

  {{Form::textarea('receive_comments', $order->receive_comments, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la entrega', 'rows' => 3])}}

</div>
<div class="form-group text-right">
  {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
</div>

{{Form::close()}}
