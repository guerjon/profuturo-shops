@extends('layouts.master')

@section('content')
  <ol class="breadcrumb">
      <a href="#" class="back-btn">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
      </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Productos</li>
  </ol>


<div class="text-right">
  <a href="{{action('AdminProductsController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Agregar producto
  </a>
</div>

  <div class="" style="margin: 20px inherit">
    
    <ul class="nav nav-tabs" role="tablist">
      @foreach($categories as $category)
        <li role="presentation" class="{{$active_tab == $category->name ? 'active' : ''}}">
          <a href="?active_tab={{$category->id}}&page=1" aria-controls="{{$category->name}}" class="tabs">
            {{$category->name}}
          </a>
        </li>
      @endforeach
    </ul>
  </div>


@if(count($products) == 0)
<div class="alert alert-warning">
  No se han creado nuevos productos
</div>
@else
  <div class="container">
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
  </div>

  <div class="text-center">

    {{$products->links()}}

  </div>
@endif

@stop
