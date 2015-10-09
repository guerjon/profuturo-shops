{{Form::open([
  'action' => ['OrderFurnituresController@postReceive', $order->id],

  ])}}

<table class="table table-striped">

  <thead>
    <tr>
      <th>
        Mobiliario
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
      <th>
       Eliminar mobiliario
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach($order->furnitures as $furniture)
    <tr>
      <td>
        {{$furniture->name}}
      </td>
      <td>
        {{$furniture->pivot->quantity}}
      </td>
      <td>
        {{Form::select("furniture[{$furniture->id}][status]",['Incompleto', 'Completo'], $furniture->pivot->status, ['class' => 'form-control'])}}
      </td>
      <td>
        {{Form::text("furniture[{$furniture->id}][comments]", $furniture->pivot->comments, ['class' => 'form-control']) }}

      </td>
      @if($order->status ==  0)
       <td>
        <button type="button" data-order-id="{{$order->id}}" class="btn btn-xs btn-danger" data-furniture-id="{{$furniture->id}}"
         data-quantity="1">Eliminar 1</button>
        <button type="button" class="btn btn-xs btn-danger" data-order-id="{{$order->id}}" data-furniture-id="{{$furniture->id}}"
         data-quantity="{{$furniture->pivot->quantity}}">Eliminar todos</button>
      </td>
      @endif


    </tr>
    @endforeach
      </tbody>

</table>
    


  <div class="form-group">
    {{Form::textarea('comments', $order->comments, ['class' => 'form-control', 'placeholder' => 'Comentarios sobre la entrega', 'rows' => 3])}}
  </div>

  <div class="form-group text-right">
    {{Form::submit('Guardar', ['class' => 'btn btn-lg btn-warning'])}}
  </div>

  <div class="form-group">
    @if($order->status == 0)
       {{link_to_action('AddFurnituresController@getIndex', 'Agregar mobiliario',[$order->id], ['class' => 'btn btn-lg btn-warning'])}}
    @endif
  </div>
  
{{Form::close()}}

@section('script')
<script charset="utf-8">
  $(function(){
    $('.table .btn-danger').click(function(){
      $.post('/api/destroy-furniture', {
        furniture_id : $(this).attr('data-furniture-id'),
        quantity   : $(this).attr('data-quantity'),
        order_id   : $(this).attr('data-order-id')
      }, function(data){
        if(data.status == 200){
          location.reload();
        }else{
          alert(data.error_msg);
        }
      });
    });
  });
</script>
@stop
