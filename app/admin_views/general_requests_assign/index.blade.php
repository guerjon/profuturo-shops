@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Asignación de solicitudes</li>
  </ol>

@if($requests->count() > 0)

<h3>Asignación de solicitudes generales</h3>

<div class="container-fluid">
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
          Fecha de solicitud
        </th>
        <th>
          Presupuesto
        </th>
        <th>
          Asignado
        </th>
        <th class="text-center">
          Asignar asesor
        </th>
        <th>
          
        </th>
        <th>
          
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($requests as $request)
      <tr class="{{$request->manager?'info':''}}">
        <td>
          {{$request->id}}
        </td>
        <td>
          {{$request->project_title}}
        </td>
        <td>
          {{$request->getStatusStrAttribute()}}
        </td>
        <td>
          {{$request->created_at->format('d-m-Y')}}
        </td>
        <td>
          {{-- money_format("%.2n",$request->unit_price * $request->quantity) --}}
          {{number_format($request->unit_price * $request->quantity,2)}}
        </td>
        <td>
          @if($request->manager != null)
              <span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span>
          @else
              <span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span>
          @endif
        </td>
        
          @if($request->manager != null)
          <td class="text-center">
            <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default assign-btn" data-request-id="{{$request->id}}">Reasignar</button>
            <p class="text-muted"><small>Asignado a: {{$request->manager->gerencia}}</small></p>
          </td>
          @else
          <td>
            <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default assign-btn" data-request-id="{{$request->id}}">Asignar</button>
          </td>
          @endif
        <td>
          <button data-toggle="modal" data-target="#show-modal" class="btn  btn-primary detail-btn" data-request-id="{{$request->id}}">
            Detalles
          </button>
        </td>
        <td>
           @if(!$request->trashed())
                {{Form::open(['action' => ['AdminGeneralRequestsAssignController@deleteDestroy',$request->id],'method' => 'DELETE'])}}
                  <button  type="submit" class="btn  btn-primary btn-danger btn-cancel">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                  </button>
                {{Form::close()}}
          @else
            {{Form::open(['action' => ['AdminGeneralRequestsAssignController@putUpdate',$request->id],'method' => 'PUT'])}}
              <button type="submit" class="btn btn-success" >
                <span class="glyphicon glyphicon-ok"></span> Habilitar
              </button>
            {{Form::close()}}
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@include('admin::general_requests_assign.partials.show')
@include('admin::general_requests_assign.partials.assign_modal')

@else

<div class="alert alert-info">
  No se han hecho solicitudes nuevas.
</div>
@endif

@stop

@section('script')
<script>
$(function(){

  $('.assign-btn').click(function(){
    $('#request_id').val($(this).attr('data-request-id'));
  });

  $('#submit-btn').click(function(){
    $('#assign-form').submit();
  });

  $('.rating-raty').raty({
    scoreName : 'rating',
    path : '/img/raty'
  });

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


        // $('input[name="evaluation"][value ='+ info.evaluation +']').prop('checked', true); 
        var date = info['deliver_date'].split(/[- :]/);

        $('#status option[value=7]').text("La fecha de tu pedido es el " + date[2] + '-' + date[1] + '-' + date[0]);          
        var product_info = data.products;
        
        for(var i = 0; i < product_info.length; i++){
          
          var name = "<tr><td>"+product_info[i].name+"</td>" + "<td>"+product_info[i].quantity+"</td>" + "<td>"+product_info[i].unit_price+"</td></tr>";
         $("#table_products").empty();
         $("#table_products").append(name);        
         }
        }
    });
  });


});
</script>
@stop
