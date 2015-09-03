@extends('layouts.master')

@section('content')

@if(isset($error))
  <div class="alert alert-danger">
    {{$error}}
  </div>
@endif
@if(isset($success))
  <div class="alert alert-success">
    {{$success}}
  </div>
@endif

@if($requests->count() == 0)
<div class="alert alert-info">
  Usted no ha hecho ninguna solicitud
</div>
@else
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
         ID de Solicitud
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
        <th>
          
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
        @if($request->status == 9)
            <td>
               <button data-toggle="modal" data-target="#request-modal-satisfaction-survey" class="btn btn-sm btn-default survey-btn"
                       data-request-id="{{$request->id}}" >
                Contestar encuesta</button> 
            </td>
        @else
            <td>
              
            </td>
        @endif
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

          <div  data-step-num="1" class="step-div ">

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

          <div  data-step-num="2" class="step-div start-div step-2">
            <div >
              <div class="row">
                <div class="col-xs-6">
                  <label>Nombre: </label>
                  <span>{{Auth::user()->nombre}}</span>
                </div>
                <div class="col-xs-6">
                  <label>Número de empleado: </label>
                  <span>{{Auth::user()->num_empleado}}</span>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <label>Extensión: </label>
                  <span>{{Auth::user()->extension}}</span>
                </div>
                <div class="col-xs-6">
                  <label>Celular: </label>
                  <span>{{Auth::user()->celular}}</span>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <label>Correo Electrónico: </label>
                  <span>{{Auth::user()->email}}</span>
                </div>
              </div>
            </div>

            <h5>Platícanos sobre tu proyecto</h5>

            <div class="form-group">
              {{Form::label('kind', 'Tipo de proyecto')}}
              <!-- {{Form::text('project_title', NULL, ['class' => 'form-control'])}} -->
              {{Form::select('kind', ['Producto', 'Servicio'], NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_dest', 'Usuarios Finales')}}
              {{Form::text('project_dest', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('project_date', 'Fecha del evento')}}
              {{Form::text('project_date', NULL, ['class' => 'form-control datepicker'])}}
            </div>
            <div class="form-group text-right">
              <button type="button" style="width:20%;" data-next-div="start-div" class="text-right btn btn-warning ">Atras</button>
              <button type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
            </div>

            </div>

          <div  data-step-num="3" class="step-div step-3">
            <h5>¿Cómo podemos ayudarte?</h5>

            <div class="form-group">
              {{Form::label('project_title', 'Nombre del proyecto')}}
              <!--{{Form::select('project_title', ['Producto', 'Servicio'], NULL, ['class' => 'form-control'])}}-->
              {{Form::text('project_title', NULL, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
              {{Form::label('deliver_date', 'Fecha de entrega')}}
              {{Form::text('deliver_date', NULL, ['class' => 'form-control datepicker'])}}
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
            <h5>¿Cuáles son tus expectativas?</h5>

            <div class="form-group">
              {{Form::textarea('comments', NULL, ['class' => 'form-control', 'rows' => 3])}}
            </div>

            <div class="form-group text-right">
              <button type="button" style="width:20%;"  data-next-div="step-3" class="text-right btn btn-warning ">Atras</button>
              <button type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
            </div>
          </div>

     <div data-step-num="5" class="step-div" >

      <div id="products">
      </div>

        <div class="form-group text-right">
              <button type="button" style="width:20%;"  data-next-div="step-3" class="text-right btn btn-warning ">Atras</button>
              <button id="addButton" type="button" style="width:30%;"  data-next-div="disabled" class="text-right btn btn-default ">Agregar Producto</button>  
              <button id="send_quantity_unit_price" type="button" style="width:20%;"  class="btn btn-warning text-rigth btn-next">Siguiente</button> 
        </div>
     </div>

      </div>
      {{ Form::close() }}
      <!-- container -->
          <div  style="display:none" id="product" class="product-form-container">
              <div class="col">
                {{Form::label('name', 'Descripción del producto o servicio')}}
                <div class="row">
                  <div class="col-xs-8">
                        {{Form::text('name[]', NULL,['class' => 'form-control'])}}
                  </div>
                  <div class="col-xs-4">
                    <button type="button"  class="btn btn-danger dismissButton">Eliminar Producto</button>     
                  </div>
              </div>
          </div>

              <div class="row">
                  <div class="col-xs-6" >

                      {{Form::label('quantity', 'Cantidad')}}
                      {{Form::text('quantity[]', NULL, ['class' => 'form-control'])}}
                  </div>
                  <div class="col-xs-6">
                      {{Form::label('unit_price', 'Presupuesto')}}
                      {{Form::text('unit_price[]', NULL, ['class' => 'form-control'])}}
                  </div>

{{--                   <div class="col-xs-4">
                      {{Form::label('budget', 'Presupuesto')}}
                      {{Form::text('budget[]', NULL,['class' => 'form-control','disabled' => 'disabled'])}}
                  </div> --}}
              </div> 
              <hr>
          </div>
        </div>        
      </div>  
  </div>
 @include('general_requests.partials.satisfaction_survey'); 
@stop

@section('script')
<script src="/js/advancedStepper.js"></script>
<script src="/js/jquery-ui.min.js" ></script>
<script src="/js/hasManyForm.js" ></script>

<script charset="utf-8">
    function calcularPresupuesto(elem){
      var parent = $(elem).parents('.product-form-container');
      console.log($(elem));
      var inputQuantity = parent.find('input[name="quantity[]"]').val();
      var inputPrice = parent.find('input[name="unit_price[]"]').val();
      var inputBudget = parent.find('input[name="budget[]"]');

      if((inputQuantity != undefined) && (inputPrice != undefined) && (inputQuantity.length > 0) && (inputPrice.length > 0)){
        inputQuantity = parseInt(inputQuantity);
        inputPrice = parseInt(inputPrice);
        inputBudget.val(inputQuantity * inputPrice);
        inputBudget.change(); 
      }
    }

  $(function(){

    $('#create-request-modal').advancedStepper();
    $('.btn-next').prop('disabled', true);
    $(document).on('keyup keydown change','div[data-step-num] input,div[data-step-num] textarea', function(){
      var llenos = true;
    $(this).parents('div[data-step-num]').find('input,textarea').each(function(){

        llenos = llenos && $(this).val() !== undefined && $(this).val().length > 0;
      });
     $(this).parents('div[data-step-num]').find('.btn-next').prop('disabled', !llenos);
    });

   //resultado de presupuesto

    $(document).on('change','input[name="quantity[]"], input[name="unit_price[]"]',function(){
      calcularPresupuesto(this);
    });


      
    $('#products').hasManyForm({
     defaultForm: '#product',
     addButton: '#addButton',
     dismissButton: '.dismissButton',
     container: '.product-form-container'
    });

    $('#addButton').click();
    $('#products .product-form-container .dismissButton').remove();

  $('.survey-btn').click(function(){
  $('#general_request_id').val($(this).attr('data-request-id'));
  console.log($(this).attr('data-request-id'));
  });



    $('#submit-btn').click(function(){
        $('#assign-form').submit();
    });

  });

</script>
@stop
