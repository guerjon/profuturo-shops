@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="#" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Usuarios sin pedidos</li>
</ol>

<div class="page-header">
  <h3>Reporte de usuarios sin pedidos</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getUserOrdersReport',
  'target' => '_blank'
  ])}}
<div class="row">
  <div class="col-xs-4 col-xs-offset-2">
    {{Form::selectMonth('month', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2">
    {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5, \Carbon\Carbon::now('America/Mexico_City')->year, \Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control'])}}
  </div>

  <button class="btn btn-primary btn-submit">
    <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
  </button>

</div>
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
  $.get('/admin/api/user-orders-report', $('#filter-form').serialize(), function(data){
    $('.table tbody').empty();
    if(data.status == 200){
      var orders = data.orders;
      var headers = ['ID', 'CENTRO_DE_COSTOS', 'GERENCIA','LINEA_NEGOCIO'];
      $('.table thead tr').empty();
      if(orders.length == 0){
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

      for(var i=0; i<headers.length; i++){
        $('.table thead tr').append($('<th>').html(headers[i]));
      }
      headers = ['id','ccosto','gerencia','linea_negocio'];
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
