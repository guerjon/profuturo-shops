
@extends('layouts.master')

@section('content')

@if(Auth::user()->cart_furnitures->count() == 0)
<div class="alert alert-warning">


  Sin artículos en el carrito. Haga click <a href="/muebles" class="alert-link">aquí</a> para ver el mobiliario disponible.

</div>
@else

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
        Compañia
      </th>
      <th>
        Bienes
      </th>
      <th>
        Centro de costos
      </th>
      <th>
        Color
      </th>
      <th>
        Id del activo
      </th>
      <th>
        Eliminar
      </th>
    </tr>
  </thead>

  <tbody>
    @foreach(Auth::user()->cart_furnitures as $furniture)
    <tr>
      <td>
        {{$furniture->name}}
      </td>

      <td>
        {{$furniture->pivot->quantity}}
      </td>
      <td>
        
      @if($furniture->pivot->company == 0)
            AFORE
          @elseif($furniture->pivot->company ==1)
            GRUPO                                  
          @elseif($furniture->pivot->company == 2)
            PENSIONES    
          @elseif($furniture->pivot->company == 4)
            FONDOS     
          @else
            INDEFINIDO
      @endif              
      </td>
      <td>
      @if($furniture->pivot->assets == 0)  
            BIENES Y ENSERES
          @elseif($furniture->pivot->assets == 1)
            OFICINA
      @endif 
      </td>
      <td>
        {{$furniture->pivot->ccostos}}
      </td>
      <td>
        <img src="{{$furniture->pivot->color}}" class="col-md-2" alt="Azul palido">
      </td>
      <td>
         {{$furniture->pivot->id_active}} 
      </td>
      <td>
        <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-furniture-id="{{$furniture->id}}" data-quantity="1">Eliminar 1</button>
        <button onclick="this.disable=true;" class="btn btn-xs btn-danger" data-furniture-id="{{$furniture->id}}" data-quantity="{{$furniture->pivot->quantity}}"
              data-company="{{$furniture->pivot->company}}" data-company = "{{$furniture->pivot->company}}" data-assets="{{$furniture->pivot->assets}}"
              data-ccosto="{{$furniture->pivot->company}}"  data-color="{{$furniture->pivot->color}}" data-active="{{$furniture->pivot->id_active}}" >Eliminar todos</button>

      </td>
    </tr>
    @endforeach

  </tbody>
</table>

<hr>

@if($last_order !== NULL and $last_order->created_at->month == \Carbon\Carbon::now()->month and Auth::user()->has_limit)
<div class="alert alert-warning">
  Usted ya realizó un pedido de mobiliario este mes.
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
        quantity : $(this).attr('data-quantity'),
        company  : $(this).attr('data-company'),
        assets   : $(this).attr('data-assets'),
        ccostos  : $(this).attr('data-ccosto'),
        color    : $(this).attr('data-color'),
        id_active: $(this).attr('data-active')
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
