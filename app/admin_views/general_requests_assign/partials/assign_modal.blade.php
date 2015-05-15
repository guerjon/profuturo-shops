<!-- Modal -->
<div class="modal fade" id="request-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignar un asesor al proyecto</h4>
      </div>
      <div class="modal-body">

        {{ Form::open([
          'action' => 'AdminGeneralRequestsAssignController@postAssign',
          'class' => 'form-horizontal',
          'id' => 'assign-form'
          ])}}

          {{Form::hidden('request_id', NULL, ['id' => 'request_id'])}}
          <div class="form-group">
            {{Form::label('rating', 'Prioridad', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              <div class="rating-raty">

              </div>
            </div>
          </div>

          <div class="form-group">
            {{Form::label('manager_id', 'Consultor', ['class' => 'control-label col-sm-4'])}}
            <div class="col-sm-8">
              {{Form::select('manager_id', $managers->lists('gerencia', 'id'), NULL, ['class' => 'form-control'])}}
            </div>
          </div>
        {{ Form::close()}}

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" id="submit-btn">Asignar</button>
      </div>
    </div>
  </div>
</div>
