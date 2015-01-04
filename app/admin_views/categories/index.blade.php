@extends('layouts.master')

@section('content')

<div class="text-right">
  {{link_to_action('AdminCategoriesController@create', 'Agregar nueva categoría', NULL, ['class' => 'btn btn-primary'])}}
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
            # de subcategorías
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
            {{$category->subcategories->count()}}
          </td>
          <td>
            {{$category->products->count()}}
          </td>
          <td>
            <button class="btn btn-sm btn-default" type="button"
              data-toggle="modal" data-target="#edit-category-modal"
              data-category-id="{{$category->id}}" data-category-name="{{$category->name}}">Editar</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="category-edit-title" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="category-edit-title">Editar categoría</h4>
        </div>
        <div class="modal-body">
          {{Form::open([
            'method' => 'PUT',
            'files' => true
            ])}}

            {{Form::hidden('category_id')}}

            <div class="form-group">
              {{Form::label('name', 'Nombre')}}

              {{Form::text('name', NULL, ['class' => 'form-control'])}}
            </div>

            <div class='form-group'>
              {{Form::label('image', 'Imagen')}}
              {{Form::file('image')}}
            </div>

            <div class="text-right">
              <button type="button" class="btn btn-sm btn-default" id="add-subcategory-btn">Añadir subcategoría</button>
            </div>

            <div id="subcategories-group">
              <div class="form-group">
                <label class="control-label">Subcategorías</label>
              </div>

              <div class="form-group" id="subcategory-cloneable" style="display:none;">
                <input type="hidden">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Nombre">
                  <span class="input-group-btn">
                    <button class="btn btn-danger btn-subcategory-remove" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  </span>
                </div>
              </div>
            </div>

            <hr>

            <div class="form-group text-right">
              {{Form::submit('Guardar', ['class' => 'btn btn-primary'])}}
            </div>

          {{Form::close()}}

        </div>
      </div>
    </div>
  </div>
@endif


@stop

@section('script')
<script charset="utf-8" src="/js/jquery-ui.min.js"></script>
<script charset="utf-8" src="/js/hasManyForm.js"></script>
<script charset="utf-8">
$(function(){
  $('#subcategories-group').hasManyForm({
    defaultForm : "#subcategory-cloneable",
    names : {
      'input[type="text"]' : 'subcategory_names[]',
      'input[type="hidden"]' : 'subcategory_ids[]'
    },
    addButton: "#add-subcategory-btn",
    dismissButton : '.btn-subcategory-remove',
  });

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
