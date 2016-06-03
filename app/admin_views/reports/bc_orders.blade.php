@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Pedidos tarjetas</li>
</ol>

<div class="page-header">
  <h3>Reporte de pedidos tarjetas</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getBcOrdersReport',
  'target' => '_blank'
  ])}}
    {{Form::hidden('page',null,['id' => 'number_page'])}}
  <div class="row">
    <div class="col-xs-3">
      {{Form::label('since','DESDE')}}
      {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
      <br>
      {{Form::label('until','HASTA')}}
      {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
  </div>

  <div class="col-xs-3">
    {{Form::label('type','TIPO DE TARJETAS')}}
    {{Form::select('type', [
     null => 'Seleccione un tipo de tarjeta',
     '1' => 'Tarjetas de presentación',
     '2' => 'Tarjetas blancas',
     '3' => 'Atracción de talento',
     '4' => 'Gerente comercial'
    ], NULL, ['class' => 'form-control'])}}
    <br>
    {{Form::label('divisional_id','DIVISIONAL')}}
    {{Form::select('divisional_id',[null => "Seleccione una divisional"] + $divisionals,null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}
     </div>
  <div class="col-xs-3">
    {{Form::label('num_pedido','NUM_PEDIDO')}}
    {{Form::text('num_pedido',null,['class' => 'form-control','placeholder' => 'Ingrese el numero de pedido','id' => 'num_pedido' ])}}
    <br>
    {{Form::label('region_id','REGIÓN ')}}
    {{Form::select('region_id',[null => "Seleccione una región"]+$regions,null,['class' => 'form-control','placeholder' => 'Ingrese la región','id' => 'region_id' ])}}
  </div>

  <div class="col-xs-2  text-right">
      <button class="btn btn-primary btn-submit">
          <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
      </button>
      {{-- <button type="button" class="btn btn-primary btn-submit" data-toggle="modal" id="grafica" data-target="#graph">
        <span class="glyphicon glyphicon-stats"></span> Gráfica
      </button> --}}
  </div>
</div>

{{Form::close()}}

<hr>


  <!-- Modal para la gráfica-------------------------------------------------------------------------- -->
  <div id="graph" class="modal fade " role="dialog">
    <div class="modal-dialog  modal-lg" style="width:70%">

      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Gráfica</h4>
        </div>
        <div class="modal-body">
          <div style="float:right; margin:1px;">
            <input type='button' class="btn btn-primary"  id="downloadReport" value='Descargar Reporte'>
          </div>
          <div style="float:right; margin:1px;">
              
            {{Form::open(['action' => 'AdminReportsController@postCreatePdf','id' => 'savePDFForm','method' => 'post'])}}
                <input type='hidden' id='htmlContentHidden' name='htmlContent' value=''>
                <input type='button' class="btn btn-primary" id="downloadBtn" value='Descargar gráfica'>
                
            {{Form::close()}}

          </div>
     
        <br>
        <br>
        <center>
          <div id="chart_div"></div>
        </center>

  
        <div id = "mamalonas" style="display:none"></div>

        <div id = "graficas" style="display:none">
          <h1>Reporte ejecutivo</h1>
          <h4>Fecha de generación {{\Carbon\Carbon::now()}} </h4>  
          <br>
        </div>
        <div class="modal-footer">
        <div class="form-group">
          <center>
          <button type="button" class="btn btn-default btn-chart" data-graph="bc_orders_type">Pedidos por tipo de tarjeta</button>
          <button type="button" class="btn btn-default btn-chart" data-graph="bc_orders_region">Pedidos por región</button>
          <button type="button" class="btn btn-default btn-chart" data-graph="bc_orders_divisional">Pedidos por Divisional</button>
          <button type="button" class="btn btn-default btn-chart" data-graph="bc_orders_status">Estatus de pedidos</button>
          </center>
        </div>
        </div>
        </div>

      </div>

    </div>
  </div>
  <!-- Termina modal -------------------------------------------------------------------------- -->



<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-responsive">
      <thead>
        <tr>

        </tr>
      </thead>

      <tbody>

      </tbody>
    </table>
  </div>
</div>

  <center>
    <ul class="pagination" id="pagination"></ul>
  </center>
@stop

@section('script')
  <script src="/js/manual_pagination.js"></script>
  <script>

    // function drawChart(datos,tipo) {

    //         var title = '';
    //         var columns = [[]];

    //         chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //         var options = {
    //                         'width': 650,
    //                         'height': 550,
    //                         legend:{position:'right'},
    //                         is3D: true
    //                        };


    //         if(tipo == 'bc_orders_type')
    //         {
    //           title = 'Pedidos por tipo';
    //           columns = [['Tipo','Cantidad']];

    //           for(var i = 0;i < datos.orders_by_type.length;i++){
    //             columns.push(datos.orders_by_type[i]);
    //           };
    //           chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //         };

    //         if(tipo == 'bc_orders_region')
    //         {
    //           title = 'Pedidos por región';
    //           columns = [['Regiones','Cantidad']]

    //           for(var i = 0;i < datos.orders_by_region.length;i++){
    //             columns.push(datos.orders_by_region[i]);
    //           };
    //            chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    //         };

    //         if(tipo == 'bc_orders_status')
    //         {
    //           title = 'Estado de pedidos';
    //           columns = [['Estado','Total']]
    //           var estado;
    //           for(var i = 0;i < datos.orders_status.length;i++){

    //             if (i == 0){
    //               estado = 'Pendiente'
    //             };
    //             if (i == 1){
    //               estado = 'Recibido'
    //             };
    //             if (i == 2){
    //               estado = 'Recibido Incompleto';
    //             };

    //             columns.push([estado,datos.orders_status[i]]);

    //             options.slices = {2: {offset: 0.2}};

    //           };
    //            chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //         };


    //         if(tipo == 'bc_orders_divisional')
    //         {
    //           title = 'Pedidos por divisional';
    //           columns = [['Divisional','Cantidad']]

    //           for(var i = 0;i < datos.orders_by_divisional.length;i++){
    //             columns.push(datos.orders_by_divisional[i]);
    //           };
    //            chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    //         };

    //         if (datos){
    //           var data = google.visualization.arrayToDataTable(columns);
    //         };

    //         options.title = title;

    //         // Instantiate and draw our chart, passing in some options.
    //         chart.draw(data, options);
            
    //         return chart;
    // }



    // function reporte(datos){
    //   //necesitamos esto para llenar las graficas que llenaran el reporte

    //   var columns_tarjeta = [[]];
    //   var columns_region = [[]];
    //   var columns_divisional = [[]];
    //   var columns_estatus = [[]];
    //   columns_tarjeta = [['Tipo','Cantidad']];
    //   columns_region = [['Regiones','Cantidad']];
    //   columns_divisional = [['Estado','Total']];
    //   columns_estatus = [['Estado','Total']];

    //   var options = {
    //                   'width': 650,
    //                   'height': 550,
    //                   legend:{position:'left'},
    //                   is3D: true
    //                  };

    //   for(var i = 0;i < datos.orders_by_type.length;i++){
    //     columns_tarjeta.push(datos.orders_by_type[i]);
    //   };

    //   for(var i = 0;i < datos.orders_by_region.length;i++){
    //     columns_region.push(datos.orders_by_region[i]);
    //   };

    //   for(var i = 0;i < datos.orders_status.length;i++){
        
    //     if (i == 0){
    //       estado = 'Pendiente'
    //     };
    //     if (i == 1){
    //       estado = 'Recibido'
    //     };
    //     if (i == 2){
    //       estado = 'Recibido Incompleto';
    //     };
        
    //     columns_estatus.push([estado,datos.orders_status[i]]);
      
    //   };

    //   for(var i = 0;i < datos.orders_by_divisional.length;i++){
    //     columns_divisional.push(datos.orders_by_divisional[i]);
    //   };


    //   console.log(columns_tarjeta);

    //   var data_tarjeta = google.visualization.arrayToDataTable(columns_tarjeta);
    //   var data_region = google.visualization.arrayToDataTable(columns_region);
    //   var data_divisional = google.visualization.arrayToDataTable(columns_divisional);
    //   var data_estatus = google.visualization.arrayToDataTable(columns_estatus);
      
    //   var chart_targeta_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
    //   var chart_region_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
    //   var chart_divisional_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
    //   var chart_estatus_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
        
    //   google.visualization.events.addListener(chart_targeta_grafica, 'ready', function ()      {
    //    $('#graficas').append('<img src="' + chart_targeta_grafica.getImageURI() + '"><br>');

    //   });

    //   google.visualization.events.addListener(chart_region_grafica, 'ready', function ()      {
    //     $('#graficas').append('<img src="' + chart_region_grafica.getImageURI() + '"><br>');
    //   });

    //   google.visualization.events.addListener(chart_divisional_grafica, 'ready', function ()      {
    //     $('#graficas').append('<img src="' + chart_divisional_grafica.getImageURI() + '"><br>');
    //   });

    //   google.visualization.events.addListener(chart_estatus_grafica, 'ready', function ()      {
    //     $('#graficas').append('<img src="' + chart_estatus_grafica.getImageURI() + '"><br>');
    //   });  

    //   chart_targeta_grafica.draw(data_tarjeta,options);
    //   chart_region_grafica.draw(data_region,options);
    //   chart_divisional_grafica.draw(data_divisional,options);
    //   chart_estatus_grafica.draw(data_estatus,options);
    // }

    function update(){

      $('.table tbody').empty();
      $('.table tbody').append(
        $('<tr>').attr('class', 'info').append(
          $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
        )
      );
      $.get('/admin/api/bc-orders-report', $('#filter-form').serialize(), function(data){

        $('.table tbody').empty();
        if(data.status == 200){
          
          //reporte(data);
          
          var orders_full = jQuery.parseJSON( data.orders_full );
          var orders = orders_full.data;
          var headers = data.headers;
          var pagination = ('#pagination');

          $('#number_page').val(orders_full.current_page);
          $('.table thead tr').empty();
          
          if(orders.length == 0){
            $('.table tbody').append(
              $('<tr>').attr('class', 'warning').append(
                $('<td>').html('<strong>No hay registros que mostrar</strong>')
              )
            );
            $('.btn-submit').prop('disabled', true);
            $('#pagination').empty();
            return;
          }else{
            $('.btn-submit').prop('disabled', false);
          }

          for(var i=0; i<headers.length; i++){
            $('.table thead tr').append($('<th>').html(headers[i]));
          }


          for(var i=0; i<orders.length; i++){
            var tr = $('<tr>');

            for(var j=0; j<headers.length; j++){
              tr.append($('<td>').html(orders[i][headers[j]]));
            }
            $('.table tbody').append(tr);
          }

          $('#pagination').empty();
          firstSpanCreate($('#pagination'),orders_full);
          if(orders_full.total > 100){
            if(orders_full.current_page > 8 && orders_full.current_page < orders_full.last_page - 2){
                if(orders_full.current_page+1 == orders_full.last_page - 3){
                  spanPointsCreate($('#pagination'));
                  listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.last_page+1);            
                }else{
                  listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.current_page+1);            
                  spanPointsCreate($('#pagination'));
                  listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);      
                }
            }else{
              listsCreate($('#pagination'),orders_full,1,9);
              spanPointsCreate($('#pagination'));
              listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);  
            }
          }else{
              listsCreate($('#pagination'),orders_full,1,orders_full.last_page+1);      
          }
           lastSpanCreate($('#pagination'),orders_full);



          //Esto se debe de poner para que al dar click en el boton se llene la grafica
          // var chart = drawChart(data,'bc_orders_type');
            

          //   $('.btn-chart').bind('click',function(){
          //      chart = drawChart(data,$(this).attr('data-graph'));
          //   });


          //   $("#downloadBtn").on("click",function(){
          //       download(chart.getImageURI(),'Grafica','image/png');
          //   });

        }else{
          $('.table tbody').append(
            $('<tr>').attr('class', 'danger').append(
              $('<td>').attr('colspan', $('.table > thead > tr th').length).html(data.status + ':' + data.error_msg)
            )
          );
        }
      });

    }

    $(function(){
      //google.load('visualization', '1', {'packages':['corechart'], "callback": drawChart});
      var data = update();

      $("#downloadReport").on("click", function() {

          var htmlContent = $("#graficas").html();

          $("#htmlContentHidden").val(htmlContent);

          // submit the form
          $('#savePDFForm').submit();

      });

      $(document).on('click', '.pagina', function(){
        event.preventDefault();
        var page = $(this).attr('data-page');
        $('#number_page').val(page);
        $('#pagination').empty();
        update();
      });

      $('#filter-form select').change(function(){
         update();
      });

      $('#num_pedido').keyup(function(){
         update();
      });

      $('#region_id').keyup(function(){
         update();
      });

      $('#ccosto').keyup(function(){
          update();
      });

      $('#until').change(function(){
          update();
      });

      $('#since').change(function(){
          update();
      });

      $.ajax({
        url : '/admin/api/bi-autocomplete',
        dataType: 'json',
        success : function(data){
          if(data.status == 200){


            var orders = data.orders;
            var ccostos = data.ccostos;

            $('#ccosto').autocomplete(
              {
                source:ccostos,
                minLength: 1
              }
            );

          }
        },error : function(data){

        }
      });

    });
  </script>

@stop
