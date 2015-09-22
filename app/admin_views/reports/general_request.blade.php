@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="#" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Solicitudes generales</li>
</ol>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getGeneralRequestsExcel',
  'target' => '_blank'
  ])}}

<div class="page-header">
  <h3>Reporte total de solicitudes generales
    <button class="btn btn-primary btn-submit" style="float:right">
      <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
    </button>
  </h3>
</div>

<div class="row"> 

</div>
{{Form::close()}}

<div class="container">
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
  $.get('/admin/api/general-request-excel', $('#filter-form').serialize(), function(data){
    $('.table tbody').empty();
    if(data.status == 200){
      var request = data.users;
      var headers = ['# de sol.', 'Título proyecto','Nombre','Numero','Estatus','Presupuesto','Fecha de solicitud','Fecha de Inicio','Fecha de entrega','Comentarios','Promedio'];
      
      $('.table thead tr').empty();
      if(request.length == 0){
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
      headers = ['id','project_title','employee_name','employee_number','status_str','total','project_date','deliver_date','deliver_date','comments','average'];
      for(var i=0; i<request.length; i++){
        var tr = $('<tr>');

        for(var j=0; j<headers.length; j++){
          tr.append($('<td>').html(request[i][headers[j]]));
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
