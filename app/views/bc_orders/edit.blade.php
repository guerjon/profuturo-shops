@extends('layouts.master')

@section('content')

{{ Form::open([
  'action' => ['BcOrdersController@update', $bc_order->id],
  'method' => 'PUT'
  ])}}

  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          Nombre
        </th>
        <th>
          Dirección
        </th>
        <th>
          Teléfono
        </th>
        <th>
          Celular
        </th>
        <th>
          Correo electrónico
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($bc_order->business_cards as $card)
      <tr>
        <td>
          {{$card->nombre}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][direccion]", $card->direccion, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][telefono]", $card->telefono, ['class' => 'form-control phone'])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][celular]", $card->celular, ['class' => 'form-control cellphone'])}}
        </td>
        <td>
          {{Form::email("card[{$card->id}][email]", $card->email, ['class' => 'form-control'])}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="form-group">
    {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 3])}}
  </div>

  @if($remaining_cards)
  <div class="form-group">
    {{ Form::label('blank_cards', '¿Desea añadir tarjetas blancas a su pedido? Recuerde que solo puede pedir 100 cada mes')}}
    @if($remaining_cards > 100)
      {{Form::select('blank_cards', [0, 100], NULL, ['class' => 'form-control'])}}
    @endif
  </div>
  @endif

  <div class="form-group text-right">
    <button type="button" id="cancel-order-button" class="btn btn-lg btn-danger">Cancelar orden</button>
    {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
  </div>
{{Form::close()}}

{{Form::open([
  'method' => 'DELETE',
  'action' => ['BcOrdersController@destroy', $bc_order->id],
  'id' => 'cancel-order-form'
  ])}}
{{Form::close()}}
@stop


@section('script')
<script src="/js/jquery.maskedinput.min.js"></script>
<script>
$(function(){
  $('input.phone').mask('9999 9999', {placeholder: '####-####'});
  $('input.cellphone').mask('99 9999 9999', {placeholder : '##-####-####'});
});
</script>
<script>
$(function(){
  $('#cancel-order-button').click(function(){
    $('#cancel-order-form').submit();
  });
});
</script>
@stop
