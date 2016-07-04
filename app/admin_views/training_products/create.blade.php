@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="/admin/products">Categorías</a></li>
    <li class="active">Añadir Producto</li>
  </ol>

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    <h3>Añadir nuevo producto</h3>
    
    @if($product->getErrors()->count() > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach($product->getErrors()->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif
    {{Form::model($product, [
      'action' => $product->exists ? ['AdminCorporationProductsController@update', $product->id] : 'AdminCorporationProductsController@store',
      'method' => $product->exists ? 'PUT' : 'POST',
      'files' => true
      ])}}

      <div class="form-group">
        {{Form::label('name', 'Nombre corto')}}
        {{Form::text('name', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('description', 'Descripción')}}
        {{Form::textarea('description', NULL, ['class' => 'form-control', 'rows' => 3])}}
      </div>

      <div class="form-group">
        {{Form::label('model', 'Modelo')}}
        {{Form::text('model', NULL, ['class' => 'form-control'])}}
      </div>

      <div class="form-group">
        {{Form::label('corporation_category_id', 'Categoría')}}
        {{Form::select('corporation_category_id', ($product->category ? [] : [NULL => 'Sin especificar']) + CorporationCategory::lists('name', 'id'), NULL,
          ['class' => 'form-control','id' => 'category_id'])}}
      </div>



      <div class="form-group">
        {{Form::label('max_stock', 'Maximo')}}
        {{Form::text('max_stock', NULL, ['class' => 'form-control'])}}
      </div>

      <div class='form-group'>
        {{Form::label('image', 'Imagen')}}
        {{Form::file('image')}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-primary btn-lg'])}}
      </div>
    {{Form::close()}}
  </div>
</div>

@stop



