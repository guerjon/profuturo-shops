@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li><a href="/admin/general-categories/index">Categorías</a></li>
    <li class="active">Categorías MAC</li>
  </ol>

<div class="text-right">
  <a href="{{action('AdminMacCategoriesController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Agregar categoría
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
          </tr>

        </thead>
        <tbody>

          @foreach($categories as $category)
          <tr>
            <td>
              {{$category->name}}
            </td>
            <td>
              
            </td>
            <td>
              <a href="{{action('AdminMacCategoriesController@edit', $category->id)}}" class="btn btn-warning btn-xs">
               <span class="glyphicon glyphicon-pencil"></span> Editar
              </a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
@endif
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

});


</script>
@stop
