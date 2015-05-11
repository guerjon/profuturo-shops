
@extends('layouts.master')

@section('content')

@if(Auth::user()->cart_furnitures->count() == 0)
<div class="alert alert-warning">
  Sin artículos en el carrito. Haga click <a href="/muebles" class="alert-link">aquí</a> para ver los muebles disponibles.
</div>
@else

<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Mueble
      </th>
      <th>
        Cantidad
      </th>
      <th>

      </th>
    </tr>
  </thead>

  <tbody>
    @foreach(Auth::user()->cart_furnitures as $furniture)
    <tr>
      <td>
        {{$furniture->name}}
      </td>

      <td class="furniture-quantity">
        {{$furniture->pivot->quantity}}
      </td>

      <td>
        <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-furniture-id="{{$furniture->id}}" data-quantity="1">Eliminar 1</button>
        <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-furniture-id="{{$furniture->id}}" data-quantity="{{$furniture->pivot->quantity}}">Eliminar todos</button>

      </td>
    </tr>
    @endforeach

  </tbody>
</table>

<hr>

@if($last_order !== NULL and $last_order->created_at->month == \Carbon\Carbon::now()->month and Auth::user()->has_limit)
<div class="alert alert-warning">
  Usted ya realizó un pedido de inmuebles este mes.
</div>
@else
&nbsp;
{{Form::open([
  'action' => 'OrderFurnituresController@store',
  'role' => 'form',
  'id' => 'send-order-form'
  ])}}

<div class="form-group">
  {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 2])}}
</div>
<div class="form-group text-right">
  <button type="submit" class="btn btn-warning">Enviar pedido</a>
</div>
{{Form::close()}}
@endif
@endif

@stop

@section('script')
<script charset="utf-8">
  $(function(){
    $('.table .btn-danger').click(function(){
      $.post('/api/remove-from-cart-furniture', {
        furniture_id : $(this).attr('data-furniture-id'),
        quantity : $(this).attr('data-quantity')
      }, function(data){
        if(data.status == 200){
          location.reload();
        }else{
          alert(data.error_msg);
        }
      });
    });

     $('form').submit(function(e){
  if(confirm("¿Esta seguro que quiere enviar este pedido, no habrá cambios después de ser enviado?")){

  }else{
    e.preventDefault();
  }
 });

  });






</script>
@stop
