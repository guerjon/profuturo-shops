@extends('layouts.master')

@section('content')

<div class="text-right">
  {{link_to_action('AdminMacProductsImporterController@create', 'Agregar un nuevo producto', NULL, ['class' => 'btn btn-warning'])}}
</div>


@if(count($furnitures) == 0)
<div class="alert alert-warning">
  No se ha creado nuevo moviliario
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
            {{$furniture->specific_description}}
          </td>
          <td>{{$furniture->category ? $furniture->category->name : 'Sin especificar'}} </td>
        

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
