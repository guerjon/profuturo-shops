@extends('layouts.master')

@section('content')
  <ol class="breadcrumb">
      <a href="#" class="back-btn">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
      </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="#">Inicio</a></li>
    <li class="active">Productos</li>
  </ol>


<div class="text-right">
  <a href="{{action('AdminProductsController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Agregar producto
  </a>
</div>


@if(count($products) == 0)
<div class="alert alert-warning">
  No se han creado nuevos productos
</div>
@else
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>
            Categoría
          </th>
          <th>
            Nombre corto
          </th>

          <th>
            Descripción
          </th>

          <th>
            Imagen
          </th>
          <th>
            Acciones
          </th>
        </tr>
      </thead>

      <tbody>
        @foreach($products as $product)
        <tr>
          <td>
            {{$product->category ? $product->category->name : 'Sin especificar'}}
          </td>
          <td>
            {{$product->name}}
          </td>
          <td>
            {{$product->description}}
          </td>

          <td>
            {{HTML::image($product->image->url('mini'))}}
          </td>

          <td>
            <a href="{{action('AdminProductsController@edit', $product->id)}}" class="btn btn-warning btn-xs">
             <span class="glyphicon glyphicon-pencil"></span> Editar
            </a>
          {{Form::open([
              'action' => ['AdminProductsController@destroy',$product->id],
              'method' => 'DELETE',
              'style' => 'display:inline'
              ])}}
          @if($product->trashed())
            <button type="submit" class="btn btn-success btn-xs">
              <span class="glyphicon glyphicon-ok"></span> Habilitar
            </button>
          @else
            <button type="submit" class="btn btn-danger btn-xs">
              <span class="glyphicon glyphicon-remove"></span> Inhabilitar
            </button>
          @endif 
          {{Form::close()}}   
          
          </td>
          
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="text-center">

    {{$products->links()}}

  </div>
@endif

@stop
