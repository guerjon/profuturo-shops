<div id="request_select_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      {{Form::open(['method' => 'put'])}}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Seleccionar objeto</h4>
        </div>

        <div class="modal-body text-center">
            
              <h4>¿Está seguro de seleccionar este objeto para la solicitud?
                <br>
               </h4>
               <p style="color:red">No se podrán hacer cambios una vez seleccionado.</p>
                <input type="hidden" class="hide" name="request_id" value="{{$request->id}}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Seleccionar</button>
        </div>
      {{Form::close()}}
    </div>

  </div>
</div>