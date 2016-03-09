@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Mayores pedidos</li>
</ol>

<div class="page-header">
  <h3>Usuarios con mayores pedidos</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getActiveUsersReport',
  'target' => '_blank'
  ])}}
<div class="row">
  <div class="col-xs-4">
    {{Form::selectMonth('month', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2">
    {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5, \Carbon\Carbon::now('America/Mexico_City')->year, \Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control'])}}
  </div>

  {{Form::hidden('page',null,['id' => 'number_page'])}}

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

   <center>
      <ul class="pagination" id="pagination" >
        
      </ul>
    </center>
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
  $.get('/admin/api/active-users-report', $('#filter-form').serialize(), function(data){
    $('.table tbody').empty();
    
    if(data.status == 200){
      
      var orders_full = jQuery.parseJSON( data.orders );
      var orders = orders_full.data;
      var headers = ['ID', 'CENTRO_DE_COSTOS', 'GERENCIA','LINEA_NEGOCIO','Cantidad'];
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
      headers = ['id','ccosto','gerencia','linea_negocio','quantity'];
      for(var i=0; i<orders.length; i++){
        var tr = $('<tr>');

        for(var j=0; j<headers.length; j++){
          tr.append($('<td>').html(orders[i][headers[j]]));
        }
        $('.table tbody').append(tr);
      }

      $('#pagination').empty();
      console.log(orders_full.current_page);
      if(orders_full.current_page == 1){
        $('#pagination').append('<li class="disabled"><span>&laquo;</span></li>') 
      }
      else{
        var page = parseInt(orders_full.current_page) - 1;
        $('#pagination').append('<li><a data-page="' +page+'" class="pagina"><span>&laquo;</span></a></li>')
      }
        

      for (var i = 1; i < orders_full.last_page +1; i++) {
        if(i == orders_full.current_page){
          $('#pagination').append('<li class="active"><a data-page="' +i+'" role="button" class="pagina"><span>' +i+'</span></a></li>');  
        }else{
          $('#pagination').append('<li><a role="button" data-page="' +i+'" class="pagina">'+i+'</a></li>'); 
        }
      };
      if(orders_full.current_page == orders_full.last_page)
        $('#pagination').append('<li class="disabled"><a data-page="' +i+'"><span>&raquo;</span></a> </li>')
      else
        $('#pagination').append('<li><a data-page="' +(orders_full.current_page+1)+'" class="pagina"><span>&raquo;</span></a> </li>')

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

  $(document).on('click', '.pagina', function(){
    event.preventDefault();
    var page = $(this).attr('data-page');
    $('#number_page').val(page);
    $('#pagination').empty();
    update();
  });

});
</script>
@stop