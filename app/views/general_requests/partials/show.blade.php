<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Solicitud <span class="request-id"></span></h4>
      </div>
      <div class="modal-body">

        <h3 id="request-project_title"></h3>

        Solicitud hecha por: <strong id="request-employee_name"></strong> con número de empleado <strong id="request-employee_number"></strong>
        <br>
        <div class="row">
          <div class="col-xs-4" style="width:50%;">
            Extensión: <strong id="request-employee_ext"></strong><br>
            Celular: <strong id="request-employee_cellphone"></strong><br>
            Correo electrónico: <strong id="request-employee_email"></strong><br>
          </div>
         
        </div>

        <hr>

         Fecha del evento: <strong id="request-project_date_formatted"></strong><br>
         Fecha de entrega: <strong id="request-deliver_date"></strong>
        <br><br><br>
       <strong>Solicitó: </strong>   <strong id="request-project_kind_st"></strong><br><br>
        <table class="table">
          <tbody>
            <tr>
              <td>
                Cantidad:  <strong id="request-quantity"></strong>
              </td>
              <td>
                Precio unitario: $ <strong id="request-unit_price"></strong>
              </td>
              <th>
                TOTAL: $ <strong id="request-total"></strong>
              </th>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-warning">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
