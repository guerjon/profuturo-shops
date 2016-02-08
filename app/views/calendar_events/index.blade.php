@extends('layouts.master')

@section('content')


<div class="calendar">

  <nav class="calendar-nav navbar navbar-default">
    <div class="container text-center">
      <ul class="navbar-nav nav navbar-left">
        <li><a href="?month={{$month['monthNum']}}&amp;year={{$month['yearNum']}}&amp;mod=prev"><span class="glyphicon glyphicon-chevron-left"></span> Anterior</a></li>
      </ul>

      <ul class="navbar-nav nav">
        <li><a>{{$month['monthName']}}</a></li>
      </ul>

      <ul class="navbar-nav nav navbar-right">
        <li><a href="?month={{$month['monthNum']}}&amp;year={{$month['yearNum']}}&amp;mod=next">Siguiente<span class="glyphicon glyphicon-chevron-right"></span></a></li>
      </ul>
    </div>
  </nav>

  @for($i=0; $i<6; $i++)
  <div class="row">

    @for($j=0; $j<7; $j++)
    @if($day = $month[(7*$i) + $j])

    <div class="col-xs-1 col-xs-calendar">
      <div class="panel panel-default panel-calendar
      {{ $day['enabled'] ? '' : 'panel-calendar-disabled'}}
      {{ $j % 7 != 0 ? '' : 'panel-calendar-holiday' }}">

      <div class="pull-right">

        @if(!$day['enabled'])
        <? $class= 'label-default' ?>
        @elseif($j% 7 == 0)
        <? $class='label-danger' ?>
        @else
        <? $make_link = true ?>
        <? $class="label-primary" ?>
        @endif

          @if(isset($make_link) and $make_link)
        <a href="{{action('CalendarEventsController@getShow',[$month['yearNum'].'-'.$month['monthNum'].'-'.$day['number']])}}">
          <span class="label {{$class}}">{{$day['number']}}</span>
        </a>

        @else
        <span class="label {{$class}}">{{$day['number']}}</span>
        @endif

      </div>
      <div class="panel-body">

        

        <div class="list-group">
          @if($day['enabled'] and count($day['events']) > 0)
          @foreach($day['events'] as $event)
          @if(Auth::user()->is_manager)
            @include('calendar_events.partials.show_as_day_item', ['event' => $event])
          @else
            @include('calendar_events.partials.show_as_day_item_manager', ['event' => $event])
          @endif
          @endforeach

          @endif

        </div>
      </div>
    </div>
  </div>
  @endif
  @endfor
</div>
@endfor
</div>

@include('general_requests.partials.show')

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

        var date = info['deliver_date'].split(/[- :]/);

        $('#status option[value=7]').text("La fecha de tu pedido es el " + date[2] + '-' + date[1] + '-' + date[0]);          
        var product_info = data.products;
        
        for(var i = 0; i < product_info.length; i++){
          
          var name = "<tr><td>"+product_info[i].name+"</td>" + "<td>"+product_info[i].quantity+"</td>" + "<td>"+product_info[i].unit_price+"</td></tr>";
          $('#table_products').empty();
          $("#table_products").append(name);        
         }
      }
    });

    $('#submit-btn').click(function(){
       $('#update-form').submit(); 
    });
    
  });
})
</script>
@stop
@stop
