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
@else




<div class="container">
  {{Form::open([
    'action' => 'BcOrdersController@store'
    ])}}

    <table class="table-striped table">
      <thead>
        <tr>
          <th style="max-width: 60px;">

          </th>

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
              No puede añadir esta tarjeta a su pedido puesto que ya hizo un pedido con fecha {{$last_order_date}}. <br>
              El pedido fue realizado con folio {{link_to_action('BcOrdersController@show', $card->id, [$card->id])}}
            @else
             {{ Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
            @endif
          </td>
        </tr>
        @endforeach
        <tr>
           <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("talent[]", $card->id)}}
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
       <tr>
           <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("manager[]", $card->id)}}
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

      <div class="text-right">
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
  $('form .btn').click(function(){
    $(this).prop('disabled', true);
    $(this).parents('form').submit();
  });
});
</script>
@stop
