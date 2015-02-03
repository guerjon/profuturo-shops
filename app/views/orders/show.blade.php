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

    </tr>
  </thead>

  <tbody>
    @foreach($order->products as $product)
    <tr>
      <td>
        {{$product->name}}
      </td>

      <td class="product-quantity">
        {{$product->pivot->quantity}}
      </td>

    </tr>
    @endforeach

    
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
        {{Form::submit('Actualizar', ['class' => 'btn btn-warning'])}}
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
