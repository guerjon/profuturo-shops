@extends('layouts.master')

@section('content')

@if($requests->count() == 0)
<div class="alert alert-info">
  Usted no ha hecho ninguna solicitud
</div>
@else
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          # de sol.
        </th>
        <th>
          Título proyecto
        </th>
        <th>
          Estatus
        </th>
        <th>
          Presupuesto
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($requests as $request)
      <tr>
        <td>
          {{$request->id}}
        </td>
        <td>
          {{$request->project_title}}
        </td>
        <td>
          {{$request->status_str}}
        </td>
        <td>
          {{ $request->unit_price * $request->quantity}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

<div class="text-right">

  <a data-toggle="modal" data-target="#create-request-modal" class="btn btn-warning">Crear nueva solicitud</a>

</div>

<div class="modal fade" id="create-request-modal" tabindex="-1" role="dialog" aria-labelledby="create-request-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="create-request-label">Crear una nueva solicitud</h4>
      </div>
      <div class="modal-body">
        {{Form::open([
          'action' => 'GeneralRequestsController@store'
          ])}}

          <div  data-step-num="1" class="step-div start-div">

            <h5>Por favor, indícanos tus datos personales para contactarte</h5>
            <h5 class="text-danger">Todos los campos son requeridos</h5>

            <div class="form-group">
              {{Form::label('employee_name', 'Nombre')}}
              {{Form::text('employee_name', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_number', 'Número de empleado')}}
              {{Form::text('employee_number', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_email', 'Correo electrónico')}}
              {{Form::email('employee_email', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_ext', 'Extensión')}}
              {{Form::text('employee_ext', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('employee_cellphone', 'Celular')}}
              {{Form::text('employee_cellphone', NULL, ['class' => 'form-control'])}}
            </div>


            <div class="form-group text-right">
              <button type="button" id="" class="btn btn-warning btn-next">Siguiente</button>
            </div>
          </div>


          <div  data-step-num="2" class="step-div step-2">
            <h5>Platícanos sobre tu proyecto</h5>

            <div class="form-group">
              {{Form::label('project_title', 'Tipo de proyecto')}}
              {{Form::text('project_title', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_dest', '¿A quién va dirigido?')}}
              {{Form::text('project_dest', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_date', 'Fecha del evento')}}
              {{Form::input('date', 'project_date', NULL, ['class' => 'form-control'])}}
            </div>
            <div class="form-group text-right">
              <button type="button" style="width:20%;" data-next-div="start-div" class="text-right btn btn-warning ">Atras</button>
              <button type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
            </div>
             
            </div>


          <div  data-step-num="3" class="step-div step-3">
            <h5>¿Cómo podemos ayudarte?</h5>

            <div class="form-group">
              {{Form::label('kind', 'Nombre del proyecto')}}
              {{Form::select('kind', ['Producto', 'Servicio'], NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('quantity', 'Cantidad')}}
              {{Form::text('quantity', NULL, ['class' => 'form-control','min'=>'1','max'=>'5'])}}
            </div>

            <div class="form-group">
              {{Form::label('unit_price', 'Precio unitario')}}
              {{Form::text('unit_price', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('budget', 'Presupuesto')}}
              {{Form::text('budget', NULL,['class' => 'form-control','disabled' => 'disabled'])}}
            </div>

            <div class="form-group">
              {{Form::label('deliver_date', 'Fecha de entrega')}}
              {{Form::input('date', 'deliver_date', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('distribution_list', 'Lista de distribución')}}
              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 1, TRUE, ['data-next-div' => 'disabled'])}} Sí
                </label>
              </div>

              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 0, false, ['data-next-div' => 'disabled'])}} No
                </label>
              </div>

              <div class="radio">
                <label>
                  {{Form::radio('distribution_list', 2, false, ['data-next-div' => 'disabled'])}} Pendiente
                </label>
              </div>
            </div>

           <div class="form-group text-right">
              <button type="button" style="width:20%;"  data-next-div="step-2"  class="text-right btn btn-warning">Atras</button>
              <button type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
            </div>
          </div>

          <div  data-step-num="4" class="step-div">
            <h5>¿Cuáles son tus expectativas</h5>

            <div class="form-group">
              {{Form::textarea('comments', NULL, ['class' => 'form-control', 'rows' => 3])}}
            </div>

            <div class="form-group text-right">
              <button type="button" style="width:20%;"  data-next-div="step-3" class="text-right btn btn-warning ">Atras</button>
              <button type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
            </div>
          </div>
        {{ Form::close() }}
      </div>

    </div>
  </div>
</div>

@stop

@section('script')
<script src="/js/advancedStepper.js"></script>
<script charset="utf-8">
    function calcularPresupuesto(){
     var val1 = $('input[name="quantity"]').val();
     var val2 = $('input[name="unit_price"]').val();
     console.log(val1);
     console.log(val2);
      if((val1 != undefined) && (val2 != undefined) && (val1.length > 0) && (val2.length > 0)){
        val1 = parseInt(val1);
        val2 = parseInt(val2);
        $('input[name="budget"]').val(val1 * val2); 
      }
    }

  $(function(){

    $('#create-request-modal').advancedStepper();
    $('.btn-next').prop('disabled', true);
    $('div[data-step-num] input,div[data-step-num] textarea').on('keyup keydown change', function(){
      var llenos = true;
      $(this).parents('div[data-step-num]').find('input,textarea').each(function(){
        
        llenos = llenos && $(this).val() !== undefined && $(this).val().length > 0;
      });
      $(this).parents('div[data-step-num]').find('.btn-next').prop('disabled', !llenos);

      
    });

   //resultado de presupuesto

   $('input[name="quantity"], input[name="unit_price"]').change(function(){
      calcularPresupuesto();
   });

  
  });



</script>
@stop
