@extends('layouts.master')

@section('content')

<ul class="nav nav-tabs">
  <li role="presentation" class="{{$activeCategory ? '' : 'active'}}">
    <a href="/corporation-productos">TODAS</a>
  </li>
  @foreach($categories as $category)
  <li role="presentation" class="{{($activeCategory !== NULL and $activeCategory->id == $category->id) ? 'active' : ''}}">
    <a href="/corporation-productos/{{$category->id}}">{{$category->name}}</a>
  </li>
  @endforeach
</ul>

<br>


  {{Form::open([
  'class' => 'form-horizontal',
  'method' => 'GET'
  ])}}

    <div class="row">
      <div class="col-xs-6"></div>
      <div class="col-xs-4">
        @if($activeCategory == null)
          {{Form::select('name',[null => 'Todos los productos'] +CorporationProduct::lists('name','name'),null,['class' => 'form-control','id' => 'name'])}}
        @else
          {{Form::select('name',[null => 'Todos los productos'] +CorporationProduct::where('corporation_category_id',$activeCategory->id)->lists('name','name'),null,['class' => 'form-control','id' => 'name'])}}
        @endif
      </div>
    <div class="col-xs-2">
      <button class="btn btn-primary">
        <span class="glyphicon glyphicon-search"></span>
          Buscar
      </button>
    </div>
  </div>
  {{Form::close()}}

<hr>

@if($products->count() == 0)
  <div class="alert alert-warning">
    No hay productos que mostrar.
  </div>
@else

  <div class="list-group">
    @foreach($products as $product)
    @unless($product->id == 10000)
    <a class="list-group-item" href="#" data-product-id="{{$product->id}}" data-image-src="{{$product->image->url('medium')}}">

      <div class="pull-right">
        
        <h5 class="product-info">Unidad de medida: {{$product->measure_unit}}</h5>
        @if(Auth::user()->cart_products->contains($product->id))
        <span class="label label-info">
          Actualmente <span class="product-current-stock">{{Auth::user()->cartProducts()->where('id', $product->id)->first()->pivot->quantity}}</span> en mi carrito
        </span>
        @endif

      </div>

      <div class="media">
        <div class="media-left">
          {{HTML::image($product->image->url('thumb'), $product->name, ['class' => 'img-rounded'])}}
        </div>

        <div class="media-body">
          <h4 class="media-heading">{{$product->name}}</h4>
          <p class="product-description">
            {{$product->description}}
          </p>
        </div>
      </div>
    </a>
    @endunless
    @endforeach
  </div>
@endif



<div class="text-center">
  <nav>
    {{$products->links()}}
  </nav>
</div>

@include('products.partials.add_to_cart_modal')

@stop

@section('script')
<script>
$(function(){
  $('#name').select2({'theme' : 'bootstrap','placeholder' : 'Nombre del producto'});
  $('a.list-group-item').click(function(){
    var modal = $('#add-to-cart-modal');
    modal.modal('show');    
    modal.find('#add-to-cart-modal-title').text($(this).find('.media-heading').text());
    modal.find('#product-cart-description').text($(this).find('.product-description').text());
    modal.find('#product-cart-info').text($(this).find('.product-info').text());
    modal.find('img').attr('src', $(this).attr('data-image-src'));
    modal.find('form input[name="product_id"]').val($(this).attr('data-product-id'));
    var select = $('form select[name="quantity"]');
    
    select.empty();

    var max = $(this).find('.product-max-stock').text();
    max = parseInt(max);

    var current = $(this).find('.product-current-stock').text();
    current = parseInt(current);

    if(isNaN(current)){
      current = 0;
    }

    var selectMax = 1000;
    
      modal.find('.alert.alert-warning').hide();
      modal.find('form').show();
      modal.find('.submit-btn').prop('disabled', false);
      for(var i=1; i<= selectMax; i++ ){
        select.append($('<option>').html(i));
      }


  });

  $('#add-to-cart-modal .submit-btn').click(function(){
    var $this = $(this);
    $this.prop('disabled', true);
    var formData = $('#add-to-cart-modal form').serialize();
    $.post('/api/add-to-cart-corporation', formData, function(data){
      if(data.status == 200){
        $('#add-to-cart-modal').modal('hide');
        alert(data.msg);
        var newq = data.new_q;
        if(newq > 0){
          var item = $('a[data-product-id="'+ data.product_id +'"]');
          if(item.length > 0){
            var label = item.find('.label');
            if(label.length == 0){
              label = $('<span>').attr('class', 'label label-info');
              span = $('<span>').attr('class', 'product-current-stock').html(newq);
              label.append('Actualmente ');
              label.append(span);
              label.append(' en mi carrito');
              item.find('.pull-right').append(label);
            }else{
              label.find('.product-current-stock').html(newq);
            }
          }
        }
      }else{
        alert(data.error_msg);
      }
      $this.prop('disabled', false);
    });
  });
});
</script>
@stop
