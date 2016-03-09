@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Pedidos mobiliario</li>
</ol>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getFurnituresOrdersReport',
  'target' => '_blank'
  ])}}
  {{Form::hidden('page',null,['id' => 'number_page'])}}
  <div class="page-header">
    <h3>Reporte de pedidos mobiliario </h3>
  <br>
  </div>

<div class="row">
  <div class="col-xs-2 ">
    {{Form::label('gerencia','GERENCIA')}}
    {{Form::select('gerencia',array_merge(array(NULL => 'Seleccione una gerencia'),$gerencia),NUll,['class' => 'form-control'])}}
    <br>
    {{Form::label('category_id','CATEGORIA')}}
    {{Form::select('category_id',array_merge(array(NULL =>'Seleccione una Categoria'),$categories),NUll,['class' => 'form-control'])}}
  </div>
 
    <div class="col-xs-4">
      {{Form::label('since','DESDE')}}
      {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
      <br>
      {{Form::label('until','HASTA')}}
      {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
    </div>
  <div class="col-xs-4">
    {{Form::label('linea_negocio','LINEA DE NEGOCIO:')}}
    {{Form::select('linea_negocio',[NULL => 'Seleccione una linea de negocio']+$business_line,NUll,['class' => 'form-control'])}}
    <br>
    {{Form::label('divisional_id','DIVISIONAL')}}
    {{Form::select('divisional_id',[null => "Seleccione una divisional"] + $divisionals,null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}

  </div>

  <div class="col-xs-2">
     <button class="btn btn-primary btn-submit"  >
        <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
      </button>

      <button type="button" class="btn btn-primary btn-submit" data-toggle="modal" id="grafica" data-target="#graph">
        <span class="glyphicon glyphicon-stats"></span> Gráfica
      </button>
  </div>

     <!-- {{ Form::submit('Descargar excel', ['class' => 'btn btn-warning btn-submit','style' => 'float:right; margin-right:10px' ])}} -->
</div>
{{Form::close()}}

<!-- Modal para la gráfica--------------------------------------------------------------------------------- -->
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

        </div>
        <div class="modal-footer">
          <div class="form-group">
            <center>
            <button type="button" class="btn btn-default btn-chart" data-graph="expensives_region">Gastos por región</button>
            <button type="button" class="btn btn-default btn-chart" data-graph="orders_region">Pedidos por región</button> 
            <button type="button" class="btn btn-default btn-chart" data-graph="orders_status">Estatus de pedidos</button>
            <button type="button" class="btn btn-default btn-chart" data-graph="orders_divisional">Pedidos por divisional</button>                       
            </center>
          </div>
        </div>
      </div>

    </div>
  </div> 

<!-- Termina modal para la gráfica--------------------------------------------------------------------------------- -->
<br>

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
    function drawChart(datos,tipo) {

                var title = '';
                var columns = [[]];

                chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                var options = {
                                'width': 650,
                                'height': 550,
                                legend:{position:'right'},
                                is3D: true
                               };
     
          

                if(tipo == 'expensives_region') 
                {
                  title = 'Gastos por Region';
                  columns = [['Región','Gasto']]
                  for(var i = 0;i < datos.expenses_by_region.length;i++){
                    columns.push(datos.expenses_by_region[i]);
                  };
                   chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                };

                if(tipo == 'orders_region')
                {
                  title = 'Pedidos por región';
                  columns = [['Región','Cantidad']]

                  for(var i = 0;i < datos.orders_by_region.length;i++){
                    columns.push(datos.orders_by_region[i]);
                  };
                   chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                };

                if(tipo == 'orders_status')
                {
                  title = 'Estado de pedidos';
                  columns = [['Estado','Total']]
                  var estado;
                  for(var i = 0;i < datos.orders_status.length;i++){

                    if (i == 0){
                      estado = 'Pendiente'
                    };
                    if (i == 1){
                      estado = 'Recibido'
                    };
                    if (i == 2){
                      estado = 'Recibido Incompleto';
                    };

                    columns.push([estado,datos.orders_status[i]]);

                    options.slices = {2: {offset: 0.2}};

                  };
                   chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                };


                if(tipo == 'orders_divisional')
                {
                  title = 'Pedidos por divisional';
                  columns = [['Divisional','Cantidad']]

                  for(var i = 0;i < datos.orders_by_divisional.length;i++){
                    columns.push(datos.orders_by_divisional[i]);
                  };
                   chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                };

                if (datos){
                  var data = google.visualization.arrayToDataTable(columns);
                };

                options.title = title;

                // Instantiate and draw our chart, passing in some options.
                chart.draw(data, options);
                
                return chart;
    }

    function reporte(datos){
      //necesitamos esto para llenar las graficas que llenaran el reporte

      var columns_region_gasto = [[]];
      var columns_region_orden= [[]];
      var columns_divisional = [[]];
      var columns_estatus = [[]];
      columns_region_gasto = [['Región','Gasto']];
      columns_region_orden= [['Región','Cantidad']];
      columns_divisional = [['Divisional','Cantidad']];
      columns_estatus = [['Estado','Total']];

      var options = {
                      'width': 650,
                      'height': 550,
                      legend:{position:'left'},
                      is3D: true
                     };
                     
      for(var i = 0;i < datos.expenses_by_region.length;i++){
        columns_region_gasto.push(datos.expenses_by_region[i]);
      };

      for(var i = 0;i < datos.orders_by_region.length;i++){
        columns_region_orden.push(datos.orders_by_region[i]);
      };

      for(var i = 0;i < datos.orders_status.length;i++){
        
        if (i == 0){
          estado = 'Pendiente'
        };
        if (i == 1){
          estado = 'Recibido'
        };
        if (i == 2){
          estado = 'Recibido Incompleto';
        };
        
        columns_estatus.push([estado,datos.orders_status[i]]);
      
      };

       for(var i = 0;i < datos.orders_by_divisional.length;i++){
        columns_divisional.push(datos.orders_by_divisional[i]);
      };

     
      var data_region = google.visualization.arrayToDataTable(columns_region_gasto);
      var data_expensives_region = google.visualization.arrayToDataTable(columns_region_orden);
      var data_divisional = google.visualization.arrayToDataTable(columns_divisional);
      var data_estatus = google.visualization.arrayToDataTable(columns_estatus);

      
      var chart_region_grafica = new google.visualization.ColumnChart(document.getElementById('mamalonas'));
      var chart_region_expensives_grafica = new google.visualization.ColumnChart(document.getElementById('mamalonas'));
      var chart_divisional_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
      var chart_estatus_grafica = new google.visualization.PieChart(document.getElementById('mamalonas'));
        

      google.visualization.events.addListener(chart_region_grafica, 'ready', function ()      {
        $('#graficas').append('<img src="' + chart_region_grafica.getImageURI() + '"><br>');
      });

      google.visualization.events.addListener(chart_region_expensives_grafica, 'ready', function ()      {
        $('#graficas').append('<img src="' + chart_region_expensives_grafica.getImageURI() + '"><br>');
      }); 

      google.visualization.events.addListener(chart_divisional_grafica, 'ready', function ()      {
        $('#graficas').append('<img src="' + chart_divisional_grafica.getImageURI() + '"><br>');
      });

      google.visualization.events.addListener(chart_estatus_grafica, 'ready', function ()      {
        $('#graficas').append('<img src="' + chart_estatus_grafica.getImageURI() + '"><br>');
      });  


      chart_region_grafica.draw(data_region,options);
      chart_region_expensives_grafica.draw(data_expensives_region,options);
      chart_divisional_grafica.draw(data_divisional,options);
      chart_estatus_grafica.draw(data_estatus,options);

    }

    function update(){
      $('.table tbody').empty();
      $('.table tbody').append(
        $('<tr>').attr('class', 'info').append(
          $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
        )
      );
      $.get('/admin/api/furnitures-orders-report', $('#filter-form').serialize(), function(data){
        $('.table tbody').empty();
        if(data.status == 200){
          reporte(data);
          
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




            var chart = drawChart(data,'expensives_region');

            $('.btn-chart').bind('click',function(){
               chart = drawChart(data,$(this).attr('data-graph'));
            });


            $("#downloadBtn").on("click",function(){
                download(chart.getImageURI(),'Grafica','image/png');
            });
          
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
      google.load('visualization', '1', {'packages':['corechart'], "callback": drawChart});
      
      var data = update();

      $(document).on('click', '.pagina', function(){
        event.preventDefault();
        var page = $(this).attr('data-page');
        $('#number_page').val(page);
        $('#pagination').empty();
        update();
      });

      $("#downloadReport").on("click", function() {
          $('#mamalonas').empty();
          var htmlContent = $("#graficas").html();

          $("#htmlContentHidden").val(htmlContent);

          // submit the form
          $('#savePDFForm').submit();

      });
      $('#filter-form select').change(function(){
        update();
      });
      $('#until').change(function(){
        update();
      });  

      $('#since').change(function(){
        update();
      });

      $('#order').keyup(function(){
        update();
      });

    });
  </script>
@stop