@extends('layouts.master')

@section('content')

<div class="container">
    <div class="col-md-4 col-md-offset-9">
  {{link_to_action('AdminFurnituresController@create', 'Agregar nuevo mueble', NULL, ['class' => 'btn btn-warning'])}}
  {{link_to_action('AdminFurnitureImporterController@create', 'Importar excel', NULL, ['class' => 'btn btn-warning'])}}
</div>


</div>


@if(count($furnitures) == 0)
<div class="alert alert-warning">
  No se han creado nuevos muebles
</div>
@else
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>
            Nombre corto
          </th>

          <th>
            Descripción
          </th>
          <th>
            Categoria
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
        @foreach($furnitures as $furniture)
        <tr>
          <td>
            {{$furniture->name}}
          </td>
          <td>
            {{$furniture->description}}
          </td>
          <td>{{$furniture->category ? $furniture->category->name : 'Sin especificar'}} </td>
          <td>
            {{HTML::image($furniture->image->url('mini'))}}
          </td>

          <td>
            {{link_to_action('AdminFurnituresController@edit', 'Editar', [$furniture->id], ['class' => 'btn btn-sm btn-default'])}}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

@endif

@stop
