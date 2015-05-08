@extends('layouts.master')

@section('content')

<div class="text-right">
  {{link_to_action('AdminProductsController@create', 'Agregar nuevo producto', NULL, ['class' => 'btn btn-warning'])}}
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
          {{link_to_action('AdminProductsController@edit', 'Editar', [$product->id], ['class' => 'btn btn-sm btn-default'])}}
          {{Form::open([
              'action' => ['AdminProductsController@destroy',$product->id],
              'method' => 'DELETE',
              'style' => 'display:inline'
              ])}}
          @if($product->trashed())
              {{Form::submit('Habilitar', ['class' => 'btn btn-info'])}}
          @else
              {{Form::submit('inhabilitar',['class' => 'btn btn-sm btn-danger'])}}
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
