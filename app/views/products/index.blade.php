@extends('layouts.master')

@section('content')

<ul class="nav nav-tabs">
  <li role="presentation" class="{{$activeCategory ? '' : 'active'}}">
    <a href="/productos">Todas</a>
  </li>
  @foreach($categories as $category)
  <li role="presentation" class="{{($activeCategory !== NULL and $activeCategory->id == $category->id) ? 'active' : ''}}">
    <a href="/productos/{{$category->id}}">{{$category->name}}</a>
  </li>
  @endforeach
</ul>

<br>

&nbsp;
<!-- <ul class="nav nav-pills">
  <li role="presentation" class="active"><a href="#">Productos</a></li>
  <li role="presentation"><a href="pedidos">Mis pedidos</a></li>
</ul>

<div class="pull-right">
  <form class="form-inline">
    <label>Buscar</label>
    <input type="text" class="form-control">
  </form>
</div>

<br>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Todos</a></li>
  <li role="presentation"><a href="#">Oficina</a></li>
  <li role="presentation"><a href="#">Móviles</a></li>
  <li role="presentation"><a href="#">Impresión</a></li>
  <li role="presentation"><a href="#">Orden</a></li>
</ul> -->

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

@if($products->count() == 0)
  <div class="alert alert-warning">
    No hay productos que mostrar.
  </div>
@else

  <div class="list-group">
    @foreach($products as $product)
    <a class="list-group-item" href="#" data-product-id="{{$product->id}}" data-image-src="{{$product->image->url('medium')}}">

      <div class="pull-right">
        <h3 class="product-price">$ {{money_format("%.2n", $product->price)}}</h3>
        @if(Auth::user()->cart_products->contains($product->id))
        <span class="label label-info">
          Actualmente {{Auth::user()->cartProducts()->where('id', $product->id)->first()->pivot->quantity}} en mi carrito
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
  $('a.list-group-item').click(function(){
    var modal = $('#add-to-cart-modal');
    modal.modal('show');
    modal.find('#add-to-cart-modal-title').text($(this).find('.media-heading').text());
    modal.find('#product-cart-description').text($(this).find('.product-description').text());
    modal.find('#product-cart-price').text($(this).find('.product-price').text());
    modal.find('img').attr('src', $(this).attr('data-image-src'));
    modal.find('form input[name="product_id"]').val($(this).attr('data-product-id'));
  });

  $('#add-to-cart-modal .submit-btn').click(function(){
    var formData = $('#add-to-cart-modal form').serialize();
    $.post('/api/add-to-cart', formData, function(data){
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
              item.find('.pull-right').append(label);
            }

            label.text('Actualmente ' + newq + ' en mi carrito');
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
