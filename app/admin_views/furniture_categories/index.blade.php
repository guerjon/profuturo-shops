	@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
      <a href="#" class="back-btn">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
      </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Categorías de mobiliario</li>
  </ol>

  <div class="text-right">
    <a href="{{action('AdminFurnitureCategoriesController@create')}}" class="btn btn-primary">
      <span class="glyphicon glyphicon-plus"></span> Agregar nueva categoría
    </a>
  </div>

@if($categories->count() == 0)

  <div class="alert alert-warning">
    No se han creado categorías
  </div>
@else

  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              Nombre
            </th>
            <th>
              # de productos
            </th>
            <th>
              Acciones
            </th>
            <th>
              Subcategorias
            </th>
          </tr>

        </thead>
        <tbody>

          @foreach($categories as $category)
          <tr>
            <td>
              {{$category->name}}
            </td>
            <td>
              {{$category->furnitures->count()}}
            </td>
            <td>
              
              <a href="{{action('AdminCategoriesController@edit', $category->id)}}" class="btn btn-warning btn-xs">
               <span class="glyphicon glyphicon-pencil"></span> Editar
              </a>              

            </td>
            <td>
              
              <a href="{{action('AdminFurnitureSubcategoriesController@create', ['furniture_category_id' => $category->id])}}" class="btn btn-default btn-sm">
                Añadir subcategoria <span class="glyphicon glyphicon-plus">
              </a>                            
          
                <button type="button" class="btn btn-default btn-sm btn-sub" data-toggle="modal" data-target="#subcategories" data-subcategory-id="{{$category->id}}"></span> Ver Subcategorias <span class="glyphicon glyphicon-eye-open"> 
                </button>
           
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@include('admin::furniture_subcategories.partials.show')
@stop



@section('script')
<script charset="utf-8">
$(function(){

  $('button[data-category-id]').click(function(){
    $('input[name="category_id"]').val($(this).attr('data-category-id'));
    $('input[name="name"]').val($(this).attr('data-category-name'));
    $('#edit-category-modal form').get(0).setAttribute('action', '/admin/categories/' + $('input[name="category_id"]').val());
    $.get('/api/subcategories/' + $(this).attr('data-category-id'), function(data){
      if(data.status == 200){
        $.each(data.subcategories, function(){
          $('#subcategories-group').hasManyForm('appendItem', {
            'subcategory_names[]' : this.name,
            'subcategory_ids[]' : this.id,
          });
        });
      }
    });
  });


  $('.btn-sub').click(function(){
        $.get('/admin/api/furnitures-subcategories/' + $(this).attr('data-subcategory-id'), function(data){
      if(data.status == 200){
        $('.furniture_subcategories').empty();
        console.log(data.subcategories)
        if (data.subcategories.length > 0){
          for (var i = 0;data.subcategories.length;i++) {
            
            
              $('.furniture_subcategories').append('<p>'+data.subcategories[i].name+'</p><br>');  
            }
          
        }else{
              $('.furniture_subcategories').append('<div class="alert alert-info">Parece que aun no se han añadido subcategorias a esta categoria</div>');  
        } 
      }
    });    
  });


});


</script>
@stop
