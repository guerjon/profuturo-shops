@extends('layouts.master')

@section('content')

<ul class="nav nav-tabs">
  <li role="presentation" class="{{$active_tab == 'assigned' ?  'active' : ''}}">
    <a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'assigned'])}}">Asignadas</a> 
  </li>
  <li role="presentation" class="{{$active_tab == 'not_assigned' ? 'active' : ''}}">
    <a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'not_assigned'])}}">No asignadas</a> 
  </li> 
</ul>


<br>
@if(Auth::user()->is_admin)
{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  ])}}
<div class="row">
  
  <div class="col-xs-2 col-xs-offset-2">
    {{Form::select('user_id',[null=>'Todos']+$users,Input::get('user_id'),['class' => 'form-control'])}}
  </div>
 
  <div class="col-xs-4">
    {{Form::selectMonth('month',Input::get('month',\Carbon\Carbon::now('America/Mexico_City')->month),['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2">
    {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5,Input::get('year',\Carbon\Carbon::now('America/Mexico_City')->year),\Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control'])}}
  </div>
    {{ Form::submit('Filtrar', ['class' => 'btn btn-warning btn-submit'])}}
  
</div>
{{Form::close()}}
@endif
@if($requests->count() > 0)

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
      <th>
        Criticidad
      </th>
      <th>
        Fecha de solicitud
      </th>
      <th>
        Fecha de Inicio
      </th>
      <th>
        Fecha de entrega
      </th>
      <th>

      </th>
    </tr>
  </thead>
  <tbody>
    @foreach($requests as $request)
    <tr>
      <td>
      {{link_to_action('AdminGeneralRequestsController@show',$request->id,['id' =>$request->id]),null}}   

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
       <div data-number="5" data-score="{{$request->rating}}" class="stars">
        
       </div> 
      </td>
      <td>
        {{$request->created_at->format('d-m-Y')}}
      </td>
      <td>
        {{$request->project_date->format('d-m-Y')}}
      </td>
      <td>
      {{$request->deliver_date->format('d-m-Y')}}
      </td>
    
      <td>
        <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default detail-btn" 
                data-request-id="{{$request->id}}">Detalles</button>
      </td>
    </tr>

    @endforeach
  </tbody>
</table>

@include('general_requests.partials.show')
  <div class="text-center">
    {{$requests->links()}}
  </div>
@else
<br>
<div class="alert alert-info">
  No se han hecho solicitudes nuevas.
</div>
@endif

@stop

@section('script')
<script>
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
         
         $("#table_products").append(name);        
         }
        }
    });
  });
  $('.stars').raty({
      
      score: function() {
        return $(this).attr('data-score');
      },
      scoreName : 'rating',
        path : '/img/raty',
        readOnly: true
  });
  $('#submit-btn').click(function(){
    $('#update-form').submit(); 
  });

});

</script>
@stop
