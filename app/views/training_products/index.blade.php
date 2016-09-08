@extends('layouts.master')

@section('content')

	<br>
<div class="container-fluid">
	<ul class="nav nav-tabs">
		<li role="presentation" class="{{$activeCategory == 'all' ? 'active' : ''}}">
			<a href="{{action('TrainingProductsController@index',['category_id' => 'all'])}}">TODAS</a>
		</li>
		@foreach(TrainingCategory::all() as $category)
		<li role="presentation" class="{{($activeCategory !== NULL and $activeCategory == $category->id) ? 'active' : ''}}">
			<a href="{{action('TrainingProductsController@index',['category_id' => $category->id])}} ">{{$category->name}}</a>
		</li>
		@endforeach
	</ul>

	<br>

	{{Form::open([
	'class' => 'form-horizontal',
	'method' => 'GET'
	])}}


	{{Form::label('name', 'Producto', ['class' => 'control-label col-xs-2 col-xs-offset-5'])}}

	<div class="col-xs-4">
		{{Form::text('name', Input::get('name'), ['class' => 'form-control'])}}
	</div>
	
	<input type="hidden" value="{{$activeCategory}}" name="category_id">

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
					@if(Auth::user()->has_limit)
					<h5 class="product-info">Max. <span class="product-max-stock">{{$product->max_stock}}</span> {{$product->measure_unit}}</h5>
					@else
					<h5 class="product-info">Unidad de medida: {{$product->measure_unit}}</h5>
					@endif
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
			@endforeach
		</div>
	@endif



	<div class="text-center">
		<nav>
			{{$products->links()}}
		</nav>
	</div>

	@include('products.partials.add_to_cart_modal')
	
</div>


@stop

@section('script')
<script>
$(function(){
	$('a.list-group-item').click(function(){
		var modal = $('#add-to-cart-modal');
		modal.modal('show');
		modal.find('#add-to-cart-modal-title').text($(this).find('.media-heading').text());
		modal.find('#product-cart-description').text($(this).find('.product-description').text());
		modal.find('#product-cart-info').text($(this).find('.product-info').text());
		modal.find('img').attr('src', $(this).attr('data-image-src'));
		modal.find('form input[name="product_id"]').val($(this).attr('data-product-id'));
		var select = $('form select[name="quantity"]');
		if(select.length == 0) return;
		select.empty();

		var max = $(this).find('.product-max-stock').text();
		max = parseInt(max);

		var current = $(this).find('.product-current-stock').text();
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
		var $this = $(this);
		$this.prop('disabled', true);
		var formData = $('#add-to-cart-modal form').serialize();
		$.post('/api/add-to-cart-training', formData, function(data){
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
