@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="#" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Reportes BI</li>
</ol>

<div class="page-header">
  <h3>Reporte de BI</h3>
</div>


{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getBIReport',
  'target' => '_blank'
  ])}}
<center>

  <div class="row">
      
    <div class="col-xs-2">
      {{Form::label('since','DESDE')}}
      {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
      <br>
      {{Form::label('until','HASTA')}}
      {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
    </div>
    
    <div class="col-xs-4">
      {{Form::label('since','# ORDEN')}}
      {{Form::text('order_id',null,['class' => 'form-control','placeholder' => 'Ingrese el numero de pedido','id' => 'order' ])}}
      <br>
      {{Form::label('ccosto','CCOSTO')}}
      {{Form::text('ccosto',null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}
    </div>

      
    <div class="col-xs-4">
      {{Form::label('category_id','CATEGORIA')}}
      {{Form::select('category_id',[null => 'Seleccione una categoria'] + $categories,null,['class' => 'form-control','id' => 'category_id'])}}
      <br>
      {{Form::label('product_id','PRODUCTO')}}
      {{Form::select('product_id',[null => 'Seleccione un producto'] +$products,null,['class' => 'form-control','id' => 'product_id'])}}
    </div>


    <div class="col-xs-2  text-right">
      <button class="btn btn-primary btn-submit">
        <span class="glyphicon glyphicon-download-alt"></span> Excel
      </button>
      <button type="button" class="btn btn-primary btn-submit" data-toggle="modal" id="grafica" data-target="#graph">
        <span class="glyphicon glyphicon-stats"></span> Gráfica
      </button>
    </div>

  </div>
</center>
{{Form::close()}}

<hr>

  <!-- Modal para la gráfica-->
  <div id="graph" class="modal fade " role="dialog">
    <div class="modal-dialog  modal-lg" style="width:70%">

      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Gráfica</h4>
        </div>
        <div class="modal-body">
          
        
      <center>
        <div id="chart_div"></div>
      </center>
        

        <div class="form-group">
          <center>
          <button type="button" class="btn btn-default btn-chart" data-graph="orders_category">Pedidos por categoria</button>
          <button type="button" class="btn btn-default btn-chart" data-graph="orders_region">Pedidos por región</button>
          <button type="button" class="btn btn-default btn-chart" data-graph="expensives_region">Gastos por región</button> 
          <button type="button" class="btn btn-default btn-chart" data-graph="orders_status">Estatus de pedidos</button>                       
          </center>
        </div>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>

    </div>
  </div> 



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


@stop

@section('script')
<script>

  function drawChart(datos,tipo) {
    console.log(datos);
    var title = '';
    var columns = [[]];
    chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    var options = {'width': 650,
                   'height': 550,
                   legend:{position:'left'},
                   is3D: true};

    if(tipo == 'orders_category') 
    {  
      title = 'Pedidos por categoría';
      columns = [['Tipo','Cantidad']]; 
      for(var i = 0;i < datos.orders_by_category.length;i++){
        columns.push(datos.orders_by_category[i]);
      };
      chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    }  

    if(tipo == 'orders_region') 
    {
      title = 'Pedidos por región';
      columns = [['Regiones','Cantidad']]
      for(var i = 0;i < datos.orders_by_region.length;i++){
        columns.push(datos.orders_by_region[i]);
      };
       chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    };

    if(tipo == 'expensives_region') 
    {
      title = 'Gastos por Region';
      columns = [['Region','Gasto']]
      for(var i = 0;i < datos.expenses_by_region.length;i++){
        columns.push(datos.expenses_by_region[i]);
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
       
        options.slices = {2: {offset: 0.4}};
      
      };
       chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    };


    if (datos){
      var data = google.visualization.arrayToDataTable(columns);
    };


    options.title = title;

    // Instantiate and draw our chart, passing in some options.
    
    
    chart.draw(data, options);
  } 

  function update(){

    $('.table tbody').empty();
    $('.table tbody').append(
      $('<tr>').attr('class', 'info').append(
        $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
      )
    );


    $.get('/admin/api/bi-report', $('#filter-form').serialize(), function(data){

      $('.table tbody').empty();
      if(data.status == 200){

        var report = data.report;
        var headers = data.headers;
        $('.table thead tr').empty();
        if(report.length == 0){
          $('.table tbody').append(
            $('<tr>').attr('class', 'warning').append(
              $('<td>').html('<strong>No hay registros que mostrar</strong>')
            )
          );
          $('.btn-submit').prop('disabled', true);
          return;
        }else{
          $('.btn-submit').prop('disabled', false);
        }

        //La longitud es -n donde n son los elementos que ocupamos en la grafica
        for(var i=0; i<headers.length-2; i++){
          $('.table thead tr').append($('<th>').html(headers[i]));
        }
        for(var i=0; i<report.length-2; i++){
          var tr = $('<tr>');

          for(var j=0; j<headers.length-2; j++){
            tr.append($('<td>').html(report[i][headers[j]]));
          }
          $('.table tbody').append(tr);


        }
       
        drawChart(data,'orders_category');
        $('.btn-chart').bind('click',function(){
          drawChart(data,$(this).attr('data-graph'));
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
    update();

    $('#filter-form select').change(function(){
       update();
    });

    $('#order').keyup(function(){
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
