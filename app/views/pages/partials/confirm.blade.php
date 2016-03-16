<!-- Modal -->
<div id="confirm-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Verifique los datos antes de enviar el pedido</h4>
      </div>
      <div class="modal-body">
        @if($user != null)
          <center>
            <h4>
              ¿La dirección de envio es correcta?
            </h4>            
          </center>
          <b>
            {{Form::textarea('posible_cambio',$user->address ? $user->address->domicilio : "Sin dirección",['class' => 'form-control','id' => 'posible_cambio'])}}            
          </b>
          <center>
            <h5>
              Sí es así presione aceptar, en caso contrarío modifique la dirección y el administrador sera notificado.    
            </h5>            
          </center>
        @else
          <h4>
            ¿Esta seguro de enviar su pedido? Recuerde que no habrá cambios.
          </h4>
        @endif
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-primary" id="btn-accept" >Aceptar</button>
        <button type="button" class="btn btn-danger"  data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
