<!-- Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar<span class="request-id"></span></h4>
      </div>
      <div class="modal-body">
        <center>
          <h3>
            <p>¿Está seguro de eliminar esta solicitud?</p>
          </h3>
          
        </center>
            
          {{Form::open(['method' => 'DELETE', 'id' => 'form-delete']) }}
          {{ Form::close()}}
        </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>



