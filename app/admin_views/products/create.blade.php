@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

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
      'action' => $product->exists ? ['AdminProductsController@update', $product->id] : 'AdminProductsController@store',
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
        {{Form::label('category_id', 'Categoría')}}
        {{Form::select('category_id', ($product->category ? [] : [NULL => 'Sin especificar']) + Category::lists('name', 'id'), NULL,
          ['class' => 'form-control'])}}
      </div>

      <div class='form-group'>
        {{Form::label('image', 'Imagen')}}
        {{Form::file('image')}}
      </div>

      <div class="form-group text-center">
        {{Form::submit('Guardar', ['class' => 'btn btn-warning btn-lg'])}}
      </div>
    {{Form::close()}}
  </div>
</div>

@stop
