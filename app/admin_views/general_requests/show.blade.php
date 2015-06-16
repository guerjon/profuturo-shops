@extends('layouts.master')

@section('content')

<h3>Detalles del la solicitud general {{$general->id}}<br>


  <div class="form-horizontal">
    <div class="form-group">
       <label class="col-sm-4 control-label">Id:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->id}}</p></div> 
    </div>
    <div class="form-group">
       
      <label class="col-sm-4 control-label">Nombre:</label>
       <div class="col-sm-8"><p class="form-control-static"> {{$general->employee_name}}  </p></div> 
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Numero:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->employee_number}} </p></div> 

          
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label"> Email:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->employee_email}} </p></div> 
  
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Extension:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->employee_ext}}   </p></div> 

        
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Celular:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->employee_cellphone}}  </p> </div> 

         
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Titulo proyecto:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->project_title}}     </p></div> 

    
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Destino:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->project_dest}} </p></div> 

           
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Fecha:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->project_date->format('d-m-Y')}} </p>
    </div> 

          
    </div>
    <div class="form-group">
       <label class="col-sm-4 control-label">Fecha de entrega:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->deliver_date->format('d-m-Y')}} </p>
    </div> 

            
    </div>
      <div class="form-group">
       <label class="col-sm-4 control-label">Comentarios:</label>
       <div class="col-sm-8"><p class="form-control-static">{{$general->comments}}</p></div>    
    </div>
    
  </div>

  
@stop
<script>
$(function(){

  $('.stars').raty({
      
      score: function() {
        return $(this).attr('data-score');
      },
      scoreName : 'rating',
        path : '/img/raty',
        readOnly: true
  });
 

});

</script>
