<!-- Modal -->
<div class="modal fade" id="show-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Solicitud<span class="request-id"></span></h4>
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
         Fecha de entrega: <strong id="request-deliver_date_formatted"></strong>
        <br><br><br>

       <strong>Solicitó: </strong>   <strong id="request-project_kind_st"></strong><br><br>
        <table class="table">
          <thead>
            <tr>
              <th>
                Nombre
              </th>      
              <th>
                Cantidad
              </th>
              <th>
                Precio
              </th>
            </tr>
          </thead>
          <tbody id = "table_products">
              
          </tbody>
        </table>
        <div>
       
          {{Form::open(array('id'=>'update-form','action' => array('GeneralRequestsController@update',0),'method' => 'put')) }}
                   {{Form::hidden('request_id')}}
          @if(Auth::user()->is_admin)
          <br>
          Estatus: <strong id="request-status_str"></strong>
          @else
          Estatus: <select name="status" id="status" class='form-control'></select>
          @endif   
          <br>                                                                      
        {{--   Evaluación:
          {{Form::radio('evaluation',1)}}1
          {{Form::radio('evaluation',2)}}2
          {{Form::radio('evaluation',3)}}3
          {{Form::radio('evaluation',4)}}4
          {{Form::radio('evaluation',5)}}5 --}}
        
          {{ Form::close()}}
        </div>
      </div>
      @if(Auth::user()->is_manager)
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-default" id="submit-btn">Guardar</button>
    
        <!-- <button type="button" class="btn btn-warning">Save changes</button> -->
      </div>
      @endif
    </div>
  </div>
</div>
