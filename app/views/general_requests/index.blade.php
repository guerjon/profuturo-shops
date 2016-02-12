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
          Fecha de solicitud
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
        <td>
          {{ $request->created_at}}
        </td>
        @if($request->status == 10)
            <td>
               <button data-toggle="modal" data-target="#request-modal-satisfaction-survey" class="btn btn-sm btn-default survey-btn"
                       data-request-id="{{$request->id}}" >
                Contestar encuesta</button> 
            </td>
        @else
      <td>
        <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default detail-btn" data-request-id="{{$request->id}}">Detalles</button>
      </td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

@if(Auth::user()->isUserRequests)

  <div class="text-right">

    <a data-toggle="modal" data-target="#create-request-modal" class="btn btn-warning">Crear nueva solicitud</a>

  </div>

@endif

@include('general_requests.partials.satisfaction_survey') 
@include('general_requests.partials.show')
@include('general_requests.partials.survey')

@stop

@section('script')
<script src="/js/advancedStepper.js"></script>
<script src="/js/jquery-ui.min.js" ></script>
<script src="/js/hasManyForm.js" ></script>

<script charset="utf-8">
  function calcularPresupuesto(elem){

    var parent = $(elem).parents('.product-form-container');
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


    $('.detail-btn').click(function(){
      $.get('/api/request-info/' + $(this).attr('data-request-id'), function(data){
        if(data.status == 200){
          var info = data.request;
          for(key in info){
            $('#request-' + key).text(info[key]);         
          }
          $('input[name="request_id"]').val(info.id); 

          var estatus = ['Acabo de recibir tu solicitud, en breve me comunicare contigo',
                       'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas',
                       'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados',
                       'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio',
                       'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad',
                       'Recotizar',
                       'Conforme a tu elección…, ingresa tu solicitud en People Soft',
                       'Ya se envió la orden de compra al proveedor',
                       '','Tu pedido llego en excelentes condiciones, en el domicilio… y recibió…',
                       'Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción.'];
          var info_status = parseInt(info.status);
          
          $("#status").empty();
          for(i = info_status; i < 10;i++){
          var opciones = "<option value='"+i+"'>"+estatus[i]+"</options>"; 
         
          $("#status").append(opciones);
          }
          
          $('select[name="status"]').val(info.status); 

          var date = info['deliver_date'].split(/[- :]/);

          $('#status option[value=8]').text("La fecha de tu pedido es el " + date[2] + '-' + date[1] + '-' + date[0]);          
          var product_info = data.products;
          
          for(var i = 0; i < product_info.length; i++){
            
            var name = "<tr><td>"+product_info[i].name+"</td>" + "<td>"+product_info[i].quantity+"</td>" + "<td>"+product_info[i].unit_price+"</td></tr>";
           $("#table_products").empty();
           $("#table_products").append(name);        
           }
          }
      });
    });

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
    });

    $('#guardar').click(function(){
      $('#update-form').submit();
    });

  });

</script>
@stop
