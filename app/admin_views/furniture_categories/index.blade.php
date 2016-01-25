	@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
      <a href="{{URL::previous()}}" class="back-btn">
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

@if(isset($success))
  <div class="alert alert-success">{{$success}}</div>
@endif

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
              
              <a href="{{action('AdminFurnitureCategoriesController@edit', $category->id)}}" class="btn btn-warning btn-xs">
               <span class="glyphicon glyphicon-pencil"></span> Editar
              </a>              

            </td>
            <td>
              
              <a href="{{action('AdminFurnitureSubcategoriesController@create', ['furniture_category_id' => $category->id])}}" class="btn btn-default btn-sm">
                Añadir subcategoria <span class="glyphicon glyphicon-plus">
              </a>                            
          
                <button type="submit" class="btn btn-default btn-sm btn-sub" data-toggle="modal" data-target="#subcategories" data-subcategory-id="{{$category->id}}"></span> Ver Subcategorias <span class="glyphicon glyphicon-eye-open"> 
                </button>
           
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="form-group">
    <table>
      <thead>
        
      </thead>
    </table>
  </div>
@endif
@include('admin::furniture_subcategories.partials.show')
@stop



@section('script')
<script charset="utf-8">
$(function(){
  var subcategoria_id = 0;
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
          

            if (data.subcategories.length > 0){

              for (var i = 0;i < data.subcategories.length;i++) {
            
                  $('.furniture_subcategories').append(data.subcategories[i].name+" "); 
                  $('.furniture_subcategories').append("<a href='subcategorias-muebles/"+data.subcategories[i].id+"/edit' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-pencil'></span> Editar</a></div></div>");

                  
                  $('.furniture_subcategories').append("<button type='submit' data-subcategory-id='"+data.subcategories[i].id+"' class='btn btn-danger btn-xs delete-subcategory'><span class='glyphicon glyphicon-remove'></span> Eliminar</button></div></div> <br><br></form>");
              }
                        
            }else{
                  $('.furniture_subcategories').append('<div class="alert alert-info">Parece que aun no se han añadido subcategorias a esta categoria</div>');  
            } 
          }
        });    
      });
  $(document).on('click', '.delete-subcategory',function(){
        event.preventDefault();
        console.log('llego aqui');

        var action = "subcategorias-muebles/"+ $(this).attr('data-subcategory-id');

        $('#form-subcategory-delete').attr('action',action);
        $('#form-subcategory-delete').submit();
  });


  


});








</script>
@stop
