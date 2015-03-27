{{Form::open([
  'action' => ['BcOrdersController@postReceive', $bc_order->id],

  ])}}

<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Nombre de empleados
      </th>
      <th>
        Cantidad
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
        {{$card->pivot->quantity}}
      </td>
      <td>
        {{Form::select("card[{$card->id}][status]",['Incompleto', 'Completo'], $card->pivot->status, ['class' => 'form-control'])}}
      </td>
      <td>
        {{Form::text("card[{$card->id}][comments]", $card->pivot->comments, ['class' => 'form-control']) }}
      </td>
    </tr>
    @endforeach


    @if($blank_card)
    
    <tr>
      <td>
        Tarjetas blancas
      </td>
      <td>
        {{$blank_card->quantity}}
      </td>
      <td>
        {{Form::select("blank_cards_status",['Incompleto', 'Completo'], $blank_card->status, ['class' => 'form-control'])}}
      </td>
      <td>
        {{Form::text("blank_cards_comments", $blank_card->comments, ['class' => 'form-control']) }}
      </td>
    </tr>
    
    @endif


    @if($bc_order->extra)
    
    <tr>
        <td>
           {{$bc_order->extra->talento_nombre}}
        </td>
        <td>
         {{$card->pivot->quantity}}
        </td>
        <td>
          {{Form::select("talento_estatus",['Incompleto', 'Completo'],$bc_order->extra->talento_estatus , ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("talento_comentarios", $bc_order->extra->talento_comentarios, ['class' => 'form-control']) }}
        </td>
    </tr>
     
    <tr> 
        <td>
           {{$bc_order->extra->gerente_nombre}}
        </td>
        <td>
          {{$card->pivot->quantity}}
        </td>
        <td>
          {{Form::select("gerente_estatus",['Incompleto', 'Completo'],$bc_order->extra->gerente_estatus, ['class' => 'form-control'])}}
        </td>
        <td>
          {{Form::text("gerente_comentarios",$bc_order->extra->gerente_comentarios, ['class' => 'form-control']) }}
        </td>
    </tr>
    
    @endif

  </tbody>

</table>

<div class="form-group">

  {{Form::textarea('receive_comments', $bc_order->receive_comments, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la entrega', 'rows' => 3])}}

</div>
<div class="form-group text-right">
  {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
</div>

{{Form::close()}}
