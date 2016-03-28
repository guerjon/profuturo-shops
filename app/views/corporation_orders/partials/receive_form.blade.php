{{Form::open([
  'action' => ['CorporationOrdersController@postReceive', $order->id],

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
      <th>
       Eliminar producto
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
      @if($order->status ==  0)
       <td>
        <button type="button" data-order-id="{{$order->id}}" class="btn btn-xs btn-danger" data-product-id="{{$product->id}}"
         data-quantity="1">Eliminar 1</button>
        <button type="button" class="btn btn-xs btn-danger" data-order-id="{{$order->id}}" data-product-id="{{$product->id}}"
         data-quantity="{{$product->pivot->quantity}}">Eliminar todos</button>
      </td>
      @endif


    </tr>
    @endforeach
      </tbody>

</table>
<div class="form-group text-right">
@if($order->status == 0)
   {{link_to_action('AddCorporationProductsController@getIndex', 'Agregar producto',[$order->id], ['class' => 'btn btn-lg btn-warning'])}}
    @endif

</div>
    


<div class="form-group">

  {{Form::textarea('receive_comments', $order->receive_comments, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la entrega', 'rows' => 3])}}

</div>
<div class="form-group text-right">
  {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
  
</div>

{{Form::close()}}

@section('script')
<script charset="utf-8">
  $(function(){
    $('.table .btn-danger').click(function(){
      $.post('/api/destroy-corporation-products', {
        product_id : $(this).attr('data-product-id'),
        quantity   : $(this).attr('data-quantity'),
        order_id   : $(this).attr('data-order-id')
      }, function(data){
        if(data.status == 200){
          location.reload();
        }else{
          alert(data.error_msg);
        }
      });
    });
  });
</script>
@stop
