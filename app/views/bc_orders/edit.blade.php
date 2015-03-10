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
          Dirección alternativa
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
           {{Form::text("card[{$card->id}][nombre]", $card->nombre, ['class' => 'form-control'])}}
        </td>
        <td>
          {{$card->direccion}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][direccion_alternativa]", $card->direccion_alternativa,['class' => 'form-control','placeholder' => 'Atracción de talento'])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][telefono]", $card->telefono, ['class' => 'form-control phone'])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][celular]", $card->celular, ['class' => 'form-control cellphone'])}}
        </td>
        <td>
          {{$card->email}}
        </td>
        
      </tr>

      @endforeach
      <tr>
        <td>
           {{Form::text("talento_nombre", NULL, ['class' => 'form-control','placeholder' => 'Atracción de talento'])}}
        </td>
        <td>
          {{Form::text("talento_direccion", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("talento_tel", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("talento_cel", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::email("talento_email", NULL, ['class' => 'form-control'])}}
        </td>
      </tr>
      <tr>
        <td>
           {{Form::text("gerente_nombre", NULL, ['class' => 'form-control','placeholder' => 'Gerente comercial'])}}
        </td>
        <td>
          {{Form::text("gerente_direccion", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("gerente_tel", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("gerente_cel", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::email("gerente_email", NULL, ['class' => 'form-control'])}}
        </td>
      </tr>
    </tbody>
  </table>

  <div class="form-group">
    {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 3])}}
  </div>

  @if($remaining_cards)
  <div class="form-group">
    {{ Form::label('blank_cards', '¿Desea añadir tarjetas blancas a su pedido? Recuerde que solo puede pedir 200 cada mes')}}
    @if($remaining_cards > 100)
      {{Form::select('blank_cards', [0,100,200], NULL, ['class' => 'form-control'])}}
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
