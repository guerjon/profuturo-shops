@extends('layouts.master')

@section('content')

<div class="page-header">
  <h3>Reporte de pedidos papelería</h3>
</div>

<div class="pull-right">
  <a href="action('AdminApiController@getOrdersReport')" ></a>
</div>

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
$(function(){
  $.get('/admin/api/orders-report', function(data){
    $('.table tbody').empty();
    if(data.status == 200){
      var orders = data.orders.data;
      for(var i=0; i<orders.length; i++){
        var products = orders[i].products;
        for(var j=0; j<products.length; j++){
          var tr = $('<tr>');
          tr.append($('<td>').html(orders[i].user.ccosto));
          tr.append($('<td>').html(''));
          tr.append($('<td>').html(products[j].sku));
          tr.append($('<td>').html(products[j].description));
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
});
</script>
@stop
