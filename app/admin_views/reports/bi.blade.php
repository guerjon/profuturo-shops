@extends('layouts.master')

@section('content')

<div class="page-header">
  <h3>Reporte de BI</h3>
</div>


{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getBIReport',
  'target' => '_blank'
  ])}}
<div class="row">
  {{-- <div class="col-xs-2 col-xs-offset-2">
    {{Form::select('region_id',$regions,null,['class' => 'form-control'])}}
  </div> --}}

    
  <div class="col-xs-1">
    {{Form::label('since','Desde')}}
    {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
  </div>
  
  <div class="col-xs-1">
    {{Form::label('until','Hasta')}}
    {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
  </div>

  <div class="col-xs-2">
     <label for="order">
        <input type="checkbox" class="checkbox-filter" data-filter="order"> #Numero de pedido 
      </label>
    {{Form::text('order_id',null,['class' => 'form-control','placeholder' => 'Ingrese el numero de pedido','id' => 'order','style' => 'display:none' ])}}
  </div>

  <div class="col-xs-2">
    <label for="order">
      <input type="checkbox" class="checkbox-filter" data-filter="ccosto"> Ccosto 
    </label>  
    {{Form::text('ccosto',null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto','style' => 'display:none'])}}
  </div>
  
  <div class="col-xs-2">
    <label for="order">
      <input type="checkbox" class="checkbox-filter" data-filter="category_id"> Categoria  
    </label>   
    {{Form::select('category_id',[null => 'Seleccione una categoria'] + $categories,null,['class' => 'form-control','id' => 'category_id','style' => 'display:none'])}}
  </div>

  <div class="col-xs-2">
    <label for="order">
      <input type="checkbox" class="checkbox-filter" data-filter="product_id"> Producto
    </label>
    {{Form::select('product_id',[null => 'Seleccione un producto'] +$products,null,['class' => 'form-control','id' => 'product_id','style' => 'display:none'])}}
  </div>

  
  <div class="col-xs-1">
    
    {{ Form::submit('Excel', ['class' => 'btn btn-warning btn-submit'])  }}
  </div>

  <div class="col-xs-1">
    <button type="button" class="btn btn-warning btn-submit" data-toggle="modal" id="grafica" data-target="#graph">Gráfica</button>
  </div>

</div>

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
          </center>
        </div>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>

    </div>
  </div> 




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


@stop

@section('script')
<script>



function drawChart(datos,tipo) {

        var title = '';
        var columns = [[]];
        chart = new google.visualization.PieChart(document.getElementById('chart_div'));
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


        if (datos){
          var data = google.visualization.arrayToDataTable(columns);
        };


        // Set chart options
        var options = {'title':title,
                       'width': 650,
                       'height': 550,
                       is3D: true};

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

  $('.checkbox-filter').change(function(){
      var campo = $(this).attr('data-filter');
      if($(this).is(':checked')){
        $('#'+campo).css('display','block'); 
      }else{
        $('#'+campo).css('display','none');  
      }
      
      
  });

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

               // $('#order').autocomplete(
               //   { 
               //     source:orders,
               //     minLength: 2
               //   }

              // );

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
