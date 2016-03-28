
@extends('layouts.master')

@section('content')

    @if(Auth::user()->cart_corporation->count() == 0)
      <div class="alert alert-warning">
        Sin artículos en el carrito. Haga click <a href="/corporation-productos" class="alert-link">aquí</a> para ver los productos disponibles.
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

            </th>
          </tr>
        </thead>

        <tbody>
          @foreach(Auth::user()->cart_corporation as $product)
          <tr>
            <td>
              {{$product->name}}
            </td>

            <td class="product-quantity">
              {{$product->pivot->quantity}}
            </td>

            <td>
              <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="1">Eliminar 1</button>
              <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-product-id="{{$product->id}}" data-quantity="{{$product->pivot->quantity}}">Eliminar todos</button>

            </td>
          </tr>
          @endforeach

        </tbody>
      </table>

      <hr>

    @if(!Auth::user()->has_limit)
        
      &nbsp;
        {{Form::open([
          'action' => 'CorporationOrdersController@store',
          'role' => 'form',
          'id' => 'send-order-form'
          ])}}

        {{Form::hidden('domicilio_original',$user->address ? $user->address->domicilio : null,['class' => 'appends'])}}
    
        <div class="form-group">
          {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 2])}}
        </div>
        <div class="form-group text-right">
          <button type="button" class="btn btn-warning btn-confirm" data-toggle="modal" data-target="#confirm-modal">Enviar pedido</button>
        </div>
        {{Form::close()}}
      @else  
          &nbsp;
          {{Form::open([
            'action' => 'CorporationOrdersController@store',
            'role' => 'form',
            'id' => 'send-order-form'
            ])}}
    
          
          {{Form::hidden('domicilio_original',$user->address ? $user->address->domicilio : null,['class' => 'appends'])}}
          <div class="form-group">
            {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 2])}}
          </div>
          <div class="form-group text-right">
            <button type="button" class="btn btn-warning btn-confirm" data-toggle="modal" data-target="#confirm-modal">Enviar pedido</button>
          </div>
          {{Form::close()}}
          
     @endif
  @endif

@include('pages.partials.confirm')  

@stop

@section('script')
<script charset="utf-8">
  $(function(){
    $('.table .btn-danger').click(function(){
      $.post('/api/remove-from-cart-corporation', {
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

  $('#btn-accept').click(function(){
     var cambio = $('#posible_cambio').clone();
     cambio.attr('hidden',true);
     cambio.appendTo('#send-order-form');
    $('#send-order-form').attr('action','/pedidos-corporativo');
    $('#send-order-form').submit();
  });

});






</script>
@stop
