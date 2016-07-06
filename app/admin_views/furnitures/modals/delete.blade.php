<div id="delete_modal_furniture" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      {{Form::open(['method' => 'DELETE','id' => 'furniture-product-form'])}}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Deshabilitar mobiliario</h4>
        </div>

        <div class="modal-body text-center">
            
              <h4>¿Está seguro de deshabilitar este mobiliario?
               
               </h4>
        </div>
        <input type="hidden" name="active_tab" value="{{$active_tab}}">
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success" id="deshabilitar">Deshabilitar</button>
        </div>
      {{Form::close()}}
    </div>

  </div>
</div>