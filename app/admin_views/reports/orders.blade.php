@extends('layouts.master')

@section('content')

<div class="page-header">
  <h3>Reporte de pedidos papelería</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getOrdersReport',
  'target' => '_blank'
  ])}}
<div class="row">
  <div class="col-xs-2 ">GERENCIA:
    {{Form::select('gerencia',$gerencia,NUll,['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2 ">CATEGORIA:
    {{Form::select('category_id',$categories,NUll,['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2 ">DESDE:
    {{Form::selectMonth('month_init', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control'])}}
  </div>

   <div class="col-xs-2 ">HASTA:
    {{Form::selectMonth('month_end', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control'])}}
  </div>
      
  <div class="col-xs-2">AÑO:
    {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5, \Carbon\Carbon::now('America/Mexico_City')->year, \Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control'])}}
  </div>
    {{ Form::submit('Descargar excel', ['class' => 'btn btn-warning btn-submit','style' => 'float:right; margin-right:10px' ])}}
</div>
{{Form::close()}}

<hr>

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

function update(){
  $('.table tbody').empty();
  $('.table tbody').append(
    $('<tr>').attr('class', 'info').append(
      $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
    )
  );
  $.get('/admin/api/orders-report', $('#filter-form').serialize(), function(data){
    $('.table tbody').empty();
    if(data.status == 200){
      var orders = data.orders;
      var headers = data.headers;
      if(orders.length == 0){
        $('.table thead tr').empty();
        $('.table tbody').append(
          $('<tr>').attr('class', 'warning').append(
            $('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>No hay registros que mostrar</strong>')
          )
        );
        $('.btn-submit').prop('disabled', true);
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
  $('#filter-form select').change(function(){
    update();
  });
});
</script>
@stop
