{{Form::open([
  'action' => ['CorporationBcOrdersController@postReceive', $bc_order->id],

  ])}}

<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleados
      </th>
      <th>
        Inmueble
      </th>
      <th>
        Telefono
      </th>
      <th>
        Celular
      </th>
      <th>
        Estatus
      </th>
      <th>
        Comentarios
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
        {{$card->pivot->inmueble}}
      </td>
      <td>
        {{$card->telefono}}
      </td>
      <td>
        {{$card->celular}}
      </td>
      <td>
        {{Form::select("card[{$card->id}][status]",['Incompleto', 'Completo'], $card->pivot->status, ['class' => 'form-control'])}}
      </td>
      <td>
        {{Form::text("card[{$card->id}][comments]", $card->pivot->comments, ['class' => 'form-control']) }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<h3>Tarjetas blancas</h3>
@if($blank_card)
  <table class="table table-striped">
    <thead>
      <th>
        Nombre de puesto
      </th>
      <th>
        Dirección alternativa
      </th>
      <th>
        Teléfono
      </th>
      <th>
        Email
      </th>
      <th>
        Comentarios
      </th>
      <th>
        Estatus
      </th>
    </thead>

    <tbody>    
      <tr>
        <td>
          {{$blank_card->nombre_puesto}}
        </td>
        <td>
          {{$blank_card->direccion_alternativa_tarjetas}}
        </td>
        <td>
          {{$blank_card->telefono_tarjetas}}
        </td>
        <td>
          {{$blank_card->email}}
        </td>
        <td>
          {{Form::text("blank_cards_comments", $blank_card->comments, ['class' => 'form-control']) }}
        </td>
        <td>
          {{Form::select("blank_cards_status",['Incompleto', 'Completo'], $blank_card->status, ['class' => 'form-control'])}}
        </td>
      </tr>
      
@endif


  @if($bc_order->extra)
    
    <tr>
        <td>
           {{$bc_order->extra->talento_nombre}}
        </td>
        <td>
          {{Form::select("talento_estatus",['Incompleto', 'Completo'],$bc_order->extra->talento_estatus , ['class' => 'form-control'])}}
        </td>
        <br>
        <td>
          {{Form::text("talento_comentarios", $bc_order->extra->talento_comentarios, ['class' => 'form-control','placeholder' => 'Comentarios Talento']) }}
        </td>
    </tr>
     
    <tr> 
        <td>
           {{$bc_order->extra->gerente_nombre}}
        </td>
        <td>
          {{Form::select("gerente_estatus",['Incompleto', 'Completo'],$bc_order->extra->gerente_estatus, ['class' => 'form-control'])}}
        </td>
        <br>
        <td>
          {{Form::text("gerente_comentarios",$bc_order->extra->gerente_comentarios, ['class' => 'form-control','placeholder' => 'Comentarios Gerente']) }}
        </td>
        
    </tr>
    
  @endif

    </tbody>

  </table>
  <br>
<div class="form-group">

  {{Form::textarea('receive_comments', $bc_order->receive_comments, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la entrega', 'rows' => 3])}}

</div>
<div class="form-group text-right">
  {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
</div>

{{Form::close()}}
