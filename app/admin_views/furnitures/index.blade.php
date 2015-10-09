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

  <div class="" style="margin: 20px inherit">
      
      @foreach($categories as $category)
      <div class="btn-group">
            
            <a href="?active_tab={{$category->id}}&page=1" aria-controls="{{$category->name}}" class="btn btn-default {{$active_tab == $category->id ? 'active' : ''}}" >
            {{$category->name}}
            </a>  
            
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            @if($category->furniture_subcategories->count() > 0)
              <ul class="dropdown-menu">
             
                @foreach($category->furniture_subcategories as $subcategory)
                  <li><a href="?active_tab={{$category->id}}&active_subtab={{$subcategory->id}}&page=1">{{$subcategory->name}}</a></li>
                @endforeach

              </ul>  
            @else
              <ul class="dropdown-menu">
                <center>
                  <li style="color:white">Sin subcategorias</li>  
                </center>
              </ul>
            @endif   
            
      </div> 
      @endforeach
  
  </div>


{{--    <div class="" style="margin: 20px inherit">
      <ul class="nav nav-tabs" role="tablist">
        @foreach($categories as $category)       
            <li role="presentation" class="{{$active_tab == $category->id ? 'active' : ''}}">
                <a href="?active_tab={{$category->id}}&page=1" aria-controls="{{$category->name}}" data-category-id="{{$category->id}}" class="tabs" id="categoria">
                {{$category->name}}
                </a>    
            </li>   
        @endforeach
      </ul>
  </div>

   <br>
  <div class="row">
    <div class="col col-md-3 col-md-offset-4">
    {{Form::select('subcategories',[null =>'Seleccione una subcategoria']+$subcategories,null, ['class' => 'form-control','id' => 'subcategoria'])}}
    </div>
  </div> --}}
  <br>
@if(count($furnitures) == 0)
<div class="alert alert-warning">
  No se ha creado nuevo mobiliario
</div>
@else
  <div class="container-fluid">
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


{{-- @section('script')
  <script type="text/javascript">
    $(function(){
      var categoria_id = $('#categoria').attr('data-category-id');
      $('#subcategoria').change(function(){
        subcategoria_id = $(this).val();
          $.ajax({
            url: "/admin/mobiliario",
            type: 'GET',
            data:{active_tab:categoria_id,active_subtab:subcategoria_id}
          });
      });
    });
  </script>
@stop --}}