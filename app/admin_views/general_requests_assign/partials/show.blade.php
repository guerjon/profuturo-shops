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
        
          Solicitud hecha por: <b><strong id="request-employee_name"></b></strong> con número de empleado <strong id="request-employee_number"></strong>
        <br>
        
          
        <div class="form-group">
              Destino de proyecto: <strong id="request-project_dest" class="label-control"></strong>
              <br>
              
              Número de empleado: <strong id="request-employee_number" class="label-control"></strong>
              <br>

                Extensión: <strong id="request-employee_ext" class="label-control"></strong>    
              
              <br>
              
                Celular: <strong id="request-employee_cellphone" class="label-control"></strong>
              
              <br>
              
                Correo electrónico: <strong id="request-employee_email" class="label-control"></strong>      
        </div>

        <hr>

         Fecha del evento: <strong id="request-project_date_formatted"></strong><br>
         Fecha de entrega: <strong id="request-deliver_date_formatted"></strong><br>
         Comentarios:  <strong id="request-comments"></strong>
        <br><br><br>

       
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
