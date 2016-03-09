@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Reportes Todos los pedidos</li>
</ol>

<div class="page-header">
  <h3>Reporte todos los pedidos</h3>
</div>


{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getBIReport',
  'target' => '_blank'
  ])}}
<center>
  {{Form::hidden('page',null,['id' => 'number_page'])}}
  <div class="row">
      
    <div class="col-xs-3">
      {{Form::label('since','DESDE')}}
      {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker change','id' => 'since' ])}}
      <br>
      {{Form::label('until','HASTA')}}
      {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker change','id' => 'until' ])}}
    </div>
    
    <div class="col-xs-3">
      {{Form::label('order_id','# ORDEN')}}
      {{Form::text('order_id',null,['class' => 'form-control keyup','placeholder' => 'Ingrese el numero de pedido','id' => 'order' ])}}
      <br>
      {{Form::label('ccosto','CCOSTO')}}
      {{Form::text('ccosto',null,['class' => 'form-control keyup','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}
    </div>

      
    <div class="col-xs-2">
      {{Form::label('category_id','CATEGORIA')}}
      {{Form::select('category_id',[null => 'Seleccione una categoria'] + $categories,null,['class' => 'form-control change','id' => 'category_id'])}}
      <br>
      {{Form::label('product_id','PRODUCTO')}}
      {{Form::select('product_id',[null => 'Seleccione un producto'] +$products,null,['class' => 'form-control change','id' => 'product_id'])}}
    </div>

    
    <div class="col-xs-2">
      {{Form::label('status','STATUS')}}
      {{Form::select('status',[null => 'Seleccione un estado de orden'] + ['0' => 'PENDIENTE','1' => 'RECIBIDO','2' => 'RECIBIDO INCOMPLETO'],null,['class' => 'form-control change','id' => 'product_id'])}}
    </div>
    
    <div class="col-xs-2">
      <button class="btn btn-primary btn-submit">
        <span class="glyphicon glyphicon-download-alt"></span> Excel
      </button>
    
    </div>
  </div>
</center>
{{Form::close()}}

<hr>

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

    //funcion que nos sirve para actualizar la tabla automaticamente al cambiar los datos en los filtros
    function update(){

      $('.table tbody').empty();
      $('.table tbody').append(
        $('<tr>').attr('class', 'info').append(
          $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
        )
      );

      $.get('/admin/api/all-products', $('#filter-form').serialize(), function(data){
        $('.table tbody').empty();
        
        if(data.status == 200){
          
          var orders_full = jQuery.parseJSON( data.orders );
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
      update();
      $("#downloadReport").on("click", function() {

          var htmlContent = $("#graficas").html();
          $("#htmlContentHidden").val(htmlContent);
          $('#savePDFForm').submit();

      });

        $(document).on('click', '.pagina', function(){
          event.preventDefault();
          var page = $(this).attr('data-page');
          $('#number_page').val(page);
          $('#pagination').empty();
          update();
        });
        
      $('.change').change(function(){
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
