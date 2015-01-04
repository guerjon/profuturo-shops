@extends('layouts.master')

@section('content')

<h3>Detalles del pedido {{$order->id}}<br><small>{{$order->comments}}</small></h3>

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
    </tr>
  </thead>

  <tbody>
    <? $total = 0 ?>
    @foreach($order->products as $product)
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
        <? $subt = $product->price * $product->pivot->quantity ?>
        <? $total += $subt ?>
        $ {{money_format("%.2n", $subt)}}
      </td>
    </tr>
    @endforeach

    <tr>
      <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
      <td>$ {{money_format("%.2n", $total)}}</td>
    </tr>
  </tbody>
</table>


@if($order->status == 1)
  <p class="well">
    Pedido recibido el día {{$order->updated_at->format('d-m-Y')}}
  </p>
@else
 
{{Form::model($order, [
  'action' => ['OrdersController@update', $order->id],
  'method' => 'PUT',
  'role' => 'form',
  'class' => 'form-horizontal'
  ])}}

  <fieldset>

    <legend>¿Ya has recibido tu pedido?</legend>
    <div class="form-group">
      {{Form::label('status', 'Estatus', ['class' => 'control-label col-md-2'])}}
      <div class="col-md-10">
        {{Form::select('status', [1 => 'Recibido completo', 2 => 'Recibido incompleto'], NULL, ['class' => 'form-control'])}}
      </div>
    </div>

    <div class="form-group complain-group" style="display:none;">
      {{Form::label('complain', 'Detalles', ['class' => 'control-label col-md-2'])}}
      <div class="col-md-10">
        {{Form::textarea('complain', NULL, ['class' => 'form-control', 'rows' => 2])}}
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-10 col-md-offset-2">
        {{Form::submit('Actualizar', ['class' => 'btn btn-primary'])}}
      </div>
    </div>

  </fieldset>
{{ Form::close()}}
@endif
@stop

@section('script')
<script charset="utf-8">
  $(function(){
    $('#status').change(function(){
      if($(this).val() == 2){
        $('.complain-group').show(200);
      }else{
        $('.complain-group').hide(200);
      }
    })
  });
</script>
@stop
