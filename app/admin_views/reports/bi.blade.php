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
  <div class="col-xs-2">
    {{Form::label('order_id','# Pedido')}}
    {{Form::text('order_id',null,['class' => 'form-control','placeholder' => 'Ingrese el numero de pedido','id' => 'order'])}}
  </div>

  <div class="col-xs-2">
    {{Form::label('CCOSTO','Ccosto')}}
    {{Form::text('ccosto',null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}
  </div>
  
  <div class="col-xs-2">
    {{Form::label('category_id','Categoria')}}
    {{Form::select('category_id',[null => 'Seleccione una categoria'] + $categories,null,['class' => 'form-control'])}}
  </div>

  <div class="col-xs-2">
    {{Form::label('product_id','Producto')}}
    {{Form::select('product_id',[null => 'Seleccione un producto'] +$products,null,['class' => 'form-control'])}}
  </div>
  
  <div class="col-xs-1">
    {{Form::label('since','Desde')}}
    {{Form::text('since', \Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
  </div>
  
  <div class="col-xs-1">
    {{Form::label('until','Hasta')}}
    {{Form::text('until', \Carbon\Carbon::now('America/Mexico_City')->addMonths('1')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
  </div>
  
  <div class="col-xs-1">
    
    {{ Form::submit('Descargar excel', ['class' => 'btn btn-warning btn-submit'])  }}
  </div>

  <div class="col-xs-1">
    <button type="button" class="btn btn-warning btn-submit" data-toggle="modal" id="grafica" data-target="#graph">Gráfica</button>
  </div>

</div>


{{Form::close()}}

<hr>

      <!-- Modal para la gráfica-->
  <div id="graph" class="modal fade " role="dialog">
    <div class="modal-dialog  modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Gráfica</h4>
        </div>
        <div class="modal-body">
          
        

        <div id="chart_div"></div>

        <div class="form-group">
          <button type="button" class="btn btn-default">Pedidos por categoria</button>
          <button type="button" class="btn btn-default">Pedidos por región</button>
          <button type="button" class="btn btn-default">Gastos por región</button>
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



function drawChart(datos) {
        console.log(datos);


        if (datos){

          var data = google.visualization.arrayToDataTable([
          ['Tipo','Cantidad'],
            datos.orders_by_category[0],
            datos.orders_by_category[1],
            datos.orders_by_category[2]
          ]);

        };


        // Set chart options
        var options = {'title':'Articulos pedidos por categoria',
                       'width':500,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
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

     
      drawChart(data);

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

<script>



      

</script>
@stop
