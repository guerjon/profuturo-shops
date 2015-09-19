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
            {{Form::label('divisional_id','Divisional')}}
            {{Form::select('divisional_id',['1' =>1,'2' => 2,'3' => 3,'4' => 4],null,['class' => 'form-control'])}}  
          </div>
          
        <div class="form-gropu">
          {{Form::label('from','Desde')}}
          {{Form::text('from',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'from' ])}}
        
          {{Form::label('until','Hasta')}}
          {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
        </div>

        {{Form::close()}}

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
       
          {{Form::submit('Guardar', ['class' => 'btn btn-primary','id' =>'btn-add-date'])}}
       
      </div>
    </div>

  </div>
</div>