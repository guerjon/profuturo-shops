@extends('layouts.master')

@section('content')
  <ul class="nav nav-tabs">
    <li role="presentation" class="{{$activeCategory ? '' : 'active'}}">
      <a href="/mobiliario">Todas</a>
    </li>
    @foreach($categories as $category)
    <li role="presentation" class="{{($activeCategory !== NULL and $activeCategory->id == $category->id) ? 'active' : ''}}">
      <a href="/mobiliario/{{$category->id}}">{{$category->name}}</a>
    </li> 
    @endforeach
  </ul>
  <br>

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
      @if($furniture->id == '10000')
      @else

        <a class="list-group-item" href="#" data-furniture-id="{{$furniture->id}}" data-image-src="{{$furniture->image->url('medium')}}"
         data-category-id="{{$furniture->furniture_category_id}}">

          <div class="pull-right">
            @if(Auth::user()->has_limit)
            <h5 class="furniture-info">Max. <span class="furniture-max-stock">{{$furniture->max_stock}}</span></h5>
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
      @endif
    @endforeach
  </div>
@endif

<div class="text-center">
  <nav>
    {{$furnitures->links()}}
  </nav>
</div>

@include('furnitures.partials.add_to_cart_modal')

@stop

@section('script')
<script>
$(function(){

      $.validator.setDefaults({
        focusCleanup: true
      });
    $( "#furniture-cart-form" ).validate({
        rules: {
          quantity: "required",
           
        },
        messages:{
          quantity: { 
                   required:"El email es requerido"
          }
          
        }
    });




  $('a.list-group-item').click(function(){
    var modal = $('#add-to-cart-modal');

    modal.modal('show');
    modal.find('#add-to-cart-modal-title').text($(this).find('.media-heading').text());
    modal.find('#furniture-cart-description').text($(this).find('.furniture-description').text());
    modal.find('#furniture-cart-info').text($(this).find('.furniture-info').text());
    modal.find('#furniture-cart-image').attr('src', $(this).attr('data-image-src'));
    modal.find('form input[name="furniture_id"]').val($(this).attr('data-furniture-id'));

    var category_id = $(this).attr('data-category-id');
    
    if (category_id == 1){
      $('#color_div').css('display','block');
    }else{
      $('#color_div').css('display','none');
    };

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
    $.post('/api/add-to-cart-furnitures', formData, function(data){
      if(data.status == 200){
        $('#add-to-cart-modal').modal('hide');
        alert(data.msg);
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
      }else{
        alert(data.error_msg);
      }
    });
  });
  });



</script>
@stop
