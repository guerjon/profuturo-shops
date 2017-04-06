<div id="add-date" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Añadir fecha</h4>
      </div>
      <div class="modal-body">
        {{Form::open(['action' => 'AdminDivisionalController@store','id' => 'form-add-date'])}}  

        <div class="form-group">
          {{Form::label('from','Desde')}}
          {{Form::text('from',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'from' ])}}
        </div>
        <div class="form-group">
          {{Form::label('until','Hasta')}}
          {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
        </div>

        <div class="form-group">
          {{Form::label('kind','Tipo')}}
          {{Form::select('kind', ['papeleria' => 'Papelería', 'tarjetas' => 'Tarjetas Presentación'], null, ['class' => 'form-control'])}}
        </div>
          {{Form::hidden('active_tab',$active_tab)}}
        {{Form::close()}}

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
       
          {{Form::submit('Guardar', ['class' => 'btn btn-primary','id' =>'btn-add-date'])}}
       
      </div>
    </div>

  </div>
</div>