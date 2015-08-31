@extends('layouts.master')

@section('content')

<div class="text-right">
      {{link_to_action('AdminFurnituresController@create', 'Agregar nuevo mobiliario', NULL, ['class' => 'btn btn-warning'])}}
      {{link_to_action('AdminFurnitureImporterController@create', 'Importar excel', NULL, ['class' => 'btn btn-warning'])}}
</div>


@if(count($furnitures) == 0)
<div class="alert alert-warning">
  No se ha creado nuevo mobiliario
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
            Unitario
          </th>
          <th>
            Tiempo de entrega
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
          <td style="max-width:30%;" >
            {{$furniture->specific_description}}
          </td>
          <td>
            {{$furniture->furniture_category ? $furniture->furniture_category->name : 'Sin especificar'}} 
          </td>
          <td>
            {{$furniture->unitary}}
          </td>
          <td>
            {{$furniture->delivery_time}}
          </td>
          <td>
            {{link_to_action('AdminFurnituresController@edit', 'Editar', [$furniture->id], ['class' => 'btn btn-sm btn-default'])}}
            
              {{Form::open([
              'class' => 'form-inline',
              'action' => ['AdminFurnituresController@destroy', $furniture->id],
              'method' => 'DELETE',
              ])}}

              @if($furniture->trashed())
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
    {{$furnitures->links()}}
  </div>
@endif


@stop
