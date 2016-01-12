@extends('layouts.master')

@section('content')

{{ Form::open([
  'action' => ['BcOrdersController@update', $bc_order->id],
  'method' => 'PUT',
  'id'     => 'form',
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
      @if(count($bc_order->business_cards) > 0)
      @foreach($bc_order->business_cards as $card)
      <tr>
        <td>
           {{Form::text("card[{$card->id}][nombre]", $card->nombre, ['class' => 'form-control'])}}
        </td>
        <td>
          {{$card->direccion}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][direccion_alternativa]", $card->direccion_alternativa,['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][telefono]", $card->telefono, ['class' => 'form-control phone','onchange'=>"$('#save').prop('disabled',false)",
          'onkeypress'=>"$('#save').prop('disabled',false)"])}}
        </td>
        <td>
          {{Form::text("card[{$card->id}][celular]", $card->celular, ['class' => 'form-control cellphone'])}}
        </td>
        <td>
           {{Form::text("card[{$card->id}][email]", $card->email, ['class' => 'form-control email'])}}
       
        </td>
        
      </tr>
      @endforeach
     @endif
      <tr>
        @if($talent == 1)

        <td>
           {{Form::text("talento_nombre", NULL, ['class' => 'form-control','placeholder' => 'Atracción de talento'])}}
        </td>
        <td>
          {{Form::text("talento_direccion", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("talento_direccion_alternativa", NULL, ['class' => 'form-control'])}}
        </td>
        </td>
        <td>
          {{Form::text("talento_tel", NULL, ['class' => 'form-control phone','onchange'=>"$('#save').prop('disabled',false)",
          'onkeypress'=>"$('#save').prop('disabled',false)"])}}
        </td>
        <td>
          {{Form::text("talento_cel", NULL, ['class' => 'form-control phone'])}}
        </td>
        <td>
          {{Form::email("talento_email", NULL, ['class' => 'form-control'])}}
        </td>
      </tr>
       @endif
       @if($manager == 1)
      <tr>
        <td>
           {{Form::text("gerente_nombre", NULL, ['class' => 'form-control','placeholder' => 'Gerente comercial'])}}
        </td>
        <td>
          {{Form::text("gerente_direccion", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("gerente_direccion_alternativa", NULL, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("gerente_tel", NULL, ['class' => 'form-control phone','onchange'=>"$('#save').prop('disabled',false)",
          'onkeypress'=>"$('#save').prop('disabled',false)"])}}
        </td>
        <td>
          {{Form::text("gerente_cel", NULL, ['class' => 'form-control phone'])}}
        </td>
        <td>
          {{Form::email("gerente_email", NULL, ['class' => 'form-control'])}}
        </td>
      </tr>
     @endif
    </tbody>
  </table>

  <div class="form-group">
    {{Form::textarea('comments', NULL, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la orden', 'rows' => 3])}}
  </div>

  @if($remaining_cards)
  <div class="form-group">
    
    {{ Form::label('blank_cards', '¿Desea añadir tarjetas blancas a su pedido? Recuerde que solo puede pedir 200 cada mes')}}
    @if($remaining_cards > 100)
    <div class="row">
      <div class="col-xs-2">
      {{Form::select('blank_cards', [0,100,200], NULL, ['class' => 'form-control'])}}
      </div>
     
      <div class="col-xs-2">
      {{Form::select('nombre_puesto',array('Asesor en Retiro' => 'Asesor en Retiro','Asesor Previsional' =>'Asesor Previsional','Ejecutivo de Cuenta'=>'Ejecutivo de cuenta'), NULL, ['class' => 'form-control'])}}
      </div>
      <div class="col-xs-5">
      {{Form::text('direccion_alternativa_tarjetas',NULL, ['class' => 'form-control','placeholder' => 'Dirección alternativa'])}}
      </div>
      <div class="col-xs-3">
      {{Form::text('telefono_tarjetas',NULL, ['class' => 'form-control','placeholder' => 'Telefono'])}}
      </div>
    </div>
    @endif
  </div>

  @else 
  Las tarjetas blancas no estan disponibles ya que se llego limite de 100. 

  @endif

  <div class="form-group text-right">
    <button type="button" id="cancel-order-button" class="btn btn-lg btn-danger">Cancelar orden</button>

    {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning','id'=>'save','disabled'])}}
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
  $('#save').click(function(){
      $(this).prop('disabled',true);
      $('#form').submit();
  });

  $('#cancel-order-button').click(function(){
    $('#cancel-order-form').submit();
  });
}
  

);
</script>
@stop
