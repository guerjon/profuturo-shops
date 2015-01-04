@extends('layouts.master')

@section('content')

@if(Auth::user()->cart_products->count() == 0)
<div class="alert alert-warning">
  Sin artículos en el carrito. Haga click <a href="/productos" class="alert-link">aquí</a> para ver los productos disponibles.
</div>
@else

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
        Precio unitario
      </th>
      <th>
        Total
      </th>
      <th>

      </th>
    </tr>
  </thead>

  <tbody>
    @foreach(Auth::user()->cart_products as $product)
    <tr>
      <td>
        {{$product->name}}
      </td>

      <td class="product-quantity">
        {{$product->pivot->quantity}}
      </td>

      <td>
        $ {{ money_format("%.2n", $product->price) }}
      </td>

      <td>
        $ {{money_format("%.2n", $product->price*$product->pivot->quantity)}}
      </td>
      <td>
        <button class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="1">Eliminar 1</button>
        <button class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="{{$product->pivot->quantity}}">Eliminar todos</button>

      </td>
    </tr>
    @endforeach

    <tr>
      <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
      <td>$ {{money_format("%.2n", Auth::user()->cart_total)}}</td>
      <td>

      </td>
    </tr>

  </tbody>
</table>

<hr>


{{Form::open([
  'action' => 'OrdersController@store',
  'role' => 'form'
  ])}}

<div class="form-group">
  {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 2])}}
</div>
<div class="form-group text-right">
  <button type="submit" class="btn btn-primary">Enviar pedido</a>
</div>
{{Form::close()}}

@endif

@stop

@section('script')
<script charset="utf-8">
  $(function(){
    $('.table .btn-danger').click(function(){
      $.post('/api/remove-from-cart', {
        product_id : $(this).attr('data-product-id'),
        quantity : $(this).attr('data-quantity')
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
