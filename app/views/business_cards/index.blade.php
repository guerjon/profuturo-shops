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
            Nombre de empleado
          </th>
          <th>
            Centro de costo
          </th>
          <th>
            Puesto
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach($cards as $card)
        <tr>
          <td style="max-width: 60px;" class="text-center">
            {{Form::checkbox("cards[]", $card->id)}}
          </td>
          <td>
            {{$card->nombre}}
          </td>
          <td>
            {{$card->ccosto}}
          </td>
          <td>
            {{ $card->nombre_puesto }}
          </td>
          <td>
            {{ Form::select("quantities[$card->id]", [1 => 100], NULL, ['class' => 'form-control'])}}
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
