@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="#" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li class="active">Mobiliario</li>
</ol>

<div class="text-right">
  <a href="{{action('AdminFurnituresController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Agregar nuevo mobiliario
  </a>
  <a href="{{action('AdminFurnitureImporterController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-import"></span> Importar Excel
  </a>
</div>


@if(count($furnitures) == 0)
<div class="alert alert-warning">
  No se ha creado nuevo mobiliario
</div>
@else
  <div class="container">
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
            <td style="max-width:30%;" >
              {{$furniture->specific_description}}
            </td>
            <td>
              {{$furniture->furniture_category ? $furniture->furniture_category->name : 'Sin especificar'}} 
            </td>
            <td>
              ${{$furniture->unitary}}
            </td>
            <td>
              {{$furniture->delivery_time}}
            </td>
            <td>
              {{HTML::image($furniture->image->url('mini'))}}
            </td>

            <td>
              <a href="{{action('AdminFurnituresController@edit', $furniture->id)}}" class="btn btn-warning btn-xs">
               <span class="glyphicon glyphicon-pencil"></span> Editar
              </a>
              <!-- {{link_to_action('AdminFurnituresController@edit', 'Editar', [$furniture->id], ['class' => 'btn btn-sm btn-default'])}} -->
              
                {{Form::open([
                'class' => 'form-inline',
                'action' => ['AdminFurnituresController@destroy', $furniture->id],
                'method' => 'DELETE',
                ])}}

                @if($furniture->trashed())
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
    {{$furnitures->links()}}
  </div>
@endif


@stop
