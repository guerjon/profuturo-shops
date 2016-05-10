<div id="external-products" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Productos externos</h4>
      </div>
      <div class="modal-body">
          {{Form::open(['method' => 'post','action' => 'ApiController@postExternalProducts'])}}
          <div class="description_container">
              <div class="form-group">
                  {{Form::label('description','Nombre del producto',['class' => 'label-control'])}}
                  {{Form::text('description[]',null,['class' => 'form-control'])}}
              </div>
              <div class="form-group">
                {{Form::label('quantity','Cantidad',['class' => 'label-control'])}}
                {{Form::selectRange('quantity[]',1,1000,null,['class' => 'form-control'])}}
              </div>
          </div>
          <div class="text-center">
                <button id="add_external_product" type="button" class="btn btn-default">
                  <span class="glyphicon glyphicon-plus"></span>
                  Añadir producto
                </button>  
              </div>
          {{Form::close()}}
      </div>
      <div class="modal-footer">

        
          <button id="save-extra-products" type="submit" class="btn btn-primary" data-dismiss="modal">
            Guardar en carrito
            <span class="glyphicon glyphicon-shopping-cart"></span>
          </button>  
      </div>
    </div>
  </div>
</div>