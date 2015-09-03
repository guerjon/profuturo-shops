@extends('layouts.master')

@section('content')

<ul class="nav nav-tabs">
  <li role="presentation" class="{{$activeCategory ? '' : 'active'}}">
    <a href="/furnitureos">TODAS</a>
  </li>
  @foreach($categories as $category)
  <li role="presentation" class="{{($activeCategory !== NULL and $activeCategory->id == $category->id) ? 'active' : ''}}">
    <a href="/furnitureos/{{$category->id}}">{{$category->name}}</a>
  </li>
  @endforeach
</ul>

<br>

&nbsp;


{{Form::open([
'class' => 'form-horizontal',
'method' => 'GET'
])}}


{{Form::label('name', 'Nombre corto', ['class' => 'control-label col-xs-2 col-xs-offset-5'])}}

<div class="col-xs-4">
  {{Form::text('name', Input::get('name'), ['class' => 'form-control'])}}
</div>


<div class="col-xs-1 text-right">
  {{Form::submit('Buscar', ['class' => 'btn btn-default'])}}
</div>
{{Form::close()}}

<hr>

@if($furnitures->count() == 0)
  <div class="alert alert-warning">
    No hay mobiliario que mostrar.
  </div>
@else

  <div class="list-group">
    @foreach($furnitures as $furniture)
    <a class="list-group-item" href="#" data-furniture-id="{{$furniture->id}}" data-image-src="{{$furniture->image->url('medium')}}">

      <div class="pull-right">
        @if(Auth::user()->has_limit)
        <h5 class="furniture-info">Max. <span class="furniture-max-stock">{{$furniture->max_stock}}</span> {{$furniture->measure_unit}}</h5>
        @else
        <h5 class="furniture-info">Unidad de medida: {{$furniture->measure_unit}}</h5>
        @endif
        @if(Auth::user()->cart_furnitures->contains($furniture->id))
        <span class="label label-info">
          Actualmente <span class="furniture-current-stock">{{Auth::user()->cartfurnitures()->where('id', $furniture->id)->first()->pivot->quantity}}</span> en mi carrito
        </span>
        @endif

      </div>

      <div class="media">
        <div class="media-left">
          {{HTML::image($furniture->image->url('thumb'), $furniture->name, ['class' => 'img-rounded'])}}
        </div>

        <div class="media-body">
          <h4 class="media-heading">{{$furniture->name}}</h4>
          <p class="furniture-description">
            {{$furniture->description}}
          </p>
        </div>

      </div>
    </a>
    @endforeach
  </div>
@endif



<div class="text-center">
  <nav>
    {{$furnitures->links()}}
  </nav>
</div>


@include('orders_furnitures.partials.add_to_order')

@stop

@section('script')
<script>
$(function(){
  $('a.list-group-item').click(function(){
    var modal = $('#add-to-cart-modal');
    modal.modal('show');
    modal.find('#add-to-cart-modal-title').text($(this).find('.media-heading').text());
    modal.find('#furniture-cart-description').text($(this).find('.furniture-description').text());
    modal.find('#furniture-cart-info').text($(this).find('.furniture-info').text());
    modal.find('img').attr('src', $(this).attr('data-image-src'));
    modal.find('form input[name="furniture_id"]').val($(this).attr('data-furniture-id'));
    var select = $('form select[name="quantity"]');
    if(select.length == 0) return;
    select.empty();

    var max = $(this).find('.furniture-max-stock').text();
    max = parseInt(max);

    var current = $(this).find('.furniture-current-stock').text();
    current = parseInt(current);

    if(isNaN(current)){
      current = 0;
    }

    var selectMax = max - current;
    selectMax = Math.min(selectMax, 50);
    if(selectMax == 0){
      modal.find('.alert.alert-warning').show();
      modal.find('form').hide();
      modal.find('.submit-btn').prop('disabled', true);
    }else{
      modal.find('.alert.alert-warning').hide();
      modal.find('form').show();
      modal.find('.submit-btn').prop('disabled', false);
      for(var i=1; i<= selectMax; i++ ){
        select.append($('<option>').html(i));
      }

    }
  });

  $('#add-to-cart-modal .submit-btn').click(function(){
    var formData = $('#add-to-cart-modal form').serialize();
    $.post('/api/add-furnitures', formData, function(data){
      if(data.status == 200){
        $('#add-to-cart-modal').modal('hide');
<<<<<<< HEAD
       window.location.replace("/pedidos-mobiliario/{{$order_id}}");
       alert("Se agrego su mobiliario exitosamente");
=======
       window.location.replace("/pedidos-mueble/{{$order_id}}");
       alert("Se agrego el mobiliario exitosamente");
>>>>>>> 59cd3775ae07015e3d5e2ccb0135ee48ffdacda2
        var newq = data.new_q;
        if(newq > 0){
          var item = $('a[data-furniture-id="'+ data.furniture_id +'"]');
          if(item.length > 0){
            var label = item.find('.label');
            if(label.length == 0){
              label = $('<span>').attr('class', 'label label-info');
              span = $('<span>').attr('class', 'furniture-current-stock').html(newq);
              label.append('Actualmente ');
              label.append(span);
              label.append(' en mi carrito');
              item.find('.pull-right').append(label);
            }else{
              label.find('.furniture-current-stock').html(newq);
            }
          }
        }
      }
      if(data.status == 500){
        alert("El mobiliario ya habia sido agregado con anterioridad")
      }

    });
  });
});
</script>
@stop
