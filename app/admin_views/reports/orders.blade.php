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
  <div class="col-xs-4 col-xs-offset-2">
    {{Form::selectMonth('month', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2">
    {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5, \Carbon\Carbon::now('America/Mexico_City')->year, \Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control'])}}
  </div>

  <div class="col-xs-4">
    {{ Form::submit('Descargar CSV', ['class' => 'btn btn-warning btn-submit'])}}
  </div>
</div>
{{Form::close()}}

<hr>

<div class="table-responsive">
  <table class="table table-responsive">
    <thead>
      <th>
        Centro de costos
      </th>
      <th>
        LOADER_BU
      </th>
      <th>
        SKU
      </th>
      <th>
        Producto
      </th>
      <th>
        Unidad de medida
      </th>
      <th>
        Cantidad requerida
      </th>
      <th>
        Precio
      </th>
      <th>
        Moneda
      </th>
      <th>
        LOCATION
      </th>
      <th>
        SHIP_TO
      </th>
      <th>
        DEPT_ID
      </th>
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

      if(orders.length == 0){
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

      for(var i=0; i<orders.length; i++){
        var products = orders[i].products;
        for(var j=0; j<products.length; j++){
          var tr = $('<tr>');
          tr.append($('<td>').html(orders[i].user.ccosto));
          tr.append($('<td>').html('0'));
          tr.append($('<td>').html(products[j].sku));
          tr.append($('<td>').html(products[j].name));
          tr.append($('<td>').html(products[j].measure_unit));
          tr.append($('<td>').html(products[j].pivot.quantity));
          tr.append($('<td>').html('0'));
          tr.append($('<td>').html('MXN'));
          tr.append($('<td>').html('0'));
          tr.append($('<td>').html('0'));
          tr.append($('<td>').html('0'));

          $('.table tbody').append(tr);

        }

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
