<!-- Modal -->
<div id="change-address" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    {{Form::open(['action' => 'AdminAddressController@show','method' => 'post','id' => 'change-address-form'])}}
    <input type="text" class="hidden" id="valor_aprobado" name="aprobado">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cambio de domicilio</h4>
      </div>
      <div class="modal-body">
        <h4>Se indico que el siguiente domicilio es incorrecto:</h4>
        <strong id="domicilio"></strong><br>
        <h4>Y que el domicilio correcto es:</h4>
        <strong id="posible_cambio"></strong><br>
        <h4>Si esto es correcto, por favor apruebe el cambio en caso contrario rechazar.</h4>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-danger approve" data-value="0">Rechazar</button>
        <button type="button" class="btn btn-success approve" data-value="1">Aprobar</button>
      </div>
    </div>

  </div>
</div>