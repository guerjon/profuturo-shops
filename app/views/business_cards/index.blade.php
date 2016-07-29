@extends('layouts.master')

@section('content')

@if($errors->count() > 0)
<div class="alert alert-danger">
  {{$errors->first()}}
</div>
@endif

@if($cards->count() == 0)
  <div class="alert alert-warning">
    No hay tarjetas de presentación disponibles
  </div>
@elseif(!$access)
  <br>
  <div class="col-xs-8 col-xs-offset-2">
    <div class="alert alert-info text-center">
        Su divisional no puede hacer pedidos por el momento o ya hizo la orden del mes.  
    </div>
  </div>
@else

<div class="container">
  {{Form::open([
    'action' => 'BcOrdersController@store'
    ])}}

    <table class="table-striped table">
      <thead>
        <tr>
          <th></th>
          <th>
            Número de empleado
          </th>

          <th>
            Nombre de empleado
          </th>
          <th>
            Puesto
          </th>
          <th>
          </th>
          <th>

          </th>
        </tr>
      </thead>
      <tbody>
        @foreach($cards as $card)
        <tr>
          <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("cards[]", $card->id, FALSE, ($forbid and @$forbidden[$card->id]) ? ['disabled' => true] : [])}}
          </td>
          <td>
            {{$card->no_emp}}
          </td>
          <td>
            {{$card->nombre}}
          </td>
          <td>
            {{ $card->nombre_puesto }}
          </td>
          <td>
            @if(($last_order_date = @$forbidden[$card->id]) and $forbid)
              <small>
                No puede añadir esta tarjeta a su pedido puesto que ya hizo un pedido con fecha {{$last_order_date}}.

              </small>
            @else
             {{ Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
            @endif
          </td>
        </tr>
        @endforeach
        <tr class=" escondido">
           <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("talent[]", $card->id,null,['id' => 'talent'])}}
          </td>
          <td>
           Talento
          </td>
          <td>

          </td>
          <td>
          </td>
          <td>
            {{Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
          </td>
      </tr>
       <tr class="escondido">
           <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("manager[]", $card->id,null,['id' => 'manager'])}}
          </td>
          <td>
          Gerente
          </td>
          <td>

          </td>
          <td>
          </td>
          <td>
            {{Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
          </td>
      </tr>
      </tbody>

    </table>

      <div class="text-right escondido">
      
        {{Form::submit('Siguiente', ['class' => 'btn btn-lg btn-warning'])}}
      
    </div>
    {{Form::close()}}

</div>

<br>

@endif


@stop

@section('script')
<script>
$(function(){

  $("input[type=checkbox]").change(function(){
      $('.escondido').show();
  });
 
  $('form .btn').click(function(event){
    event.preventDefault();
    var n = $( "input:checked" ).length;
    
    if ($("#talent").is(":checked"))
      n = n-1;
    
    if ($("#manager").is(":checked"))
      n = n-1;
    
    if(n >= 1){
      $(this).prop('disabled', true);
      $(this).parents('form').submit();
    }else{
      alert('Se debe de seleccionar por lo menos un usuario.');
    }

  });

  $('')
});
</script>
@stop
