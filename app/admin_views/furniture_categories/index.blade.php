	@extends('layouts.master')

@section('content')

<div class="text-right">
  {{link_to_action('AdminFurnitureCategoriesController@create', 'Agregar nueva categoría', NULL, ['class' => 'btn btn-warning'])}}
</div>


@if($categories->count() == 0)

  <div class="alert alert-warning">
    No se han creado categorías
  </div>
@else

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
            {{$category->furnitures->count()}}
          </td>
          <td>
            {{link_to_action('AdminCategoriesController@edit', 'Editar', [$category->id], ['class' => 'btn btn-default btn-sm']) }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
