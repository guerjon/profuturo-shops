<div id="select-product" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Seleccionar producto</h4>
      </div>
      <div class="modal-body text-center">
        {{Form::open(['method' => 'put'])}}
          <input type="hidden" value="" name="request_product_id" id="request_product_id">
          <p>¿Está seguro de seleccionar este producto, no habrá cambios una vez seleccionado?</p>
        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" data-product-id="" data-request-id=""  class="btn btn-primary" data-dismiss="modal" id="accept-product-btn">Aceptar</button>
      </div>
    </div>
  </div>
</div>