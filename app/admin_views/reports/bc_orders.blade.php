@extends('layouts.master')

@section('content')

<div class="page-header">
  <h3>Reporte de pedidos papelería</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminApiController@getBcOrdersReport',
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
        Número de pedido
      </th>
      <th>
        Gerencia
      </th>
      <th>
        Fecha de pedido
      </th>
      <th>
        Nombre empleado
      </th>
      <th>
        Nombre puesto
      </th>
      <th>
        Correo electrónico
      </th>
      <th>
        Teléfono
      </th>
      <th>
        Celular
      </th>
      <th>
        Web
      </th>
      <th>
        Centro de costo
      </th>
      <th>
        Dirección
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
  $.get('/admin/api/bc-orders-report', $('#filter-form').serialize(), function(data){
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
        var business_cards = orders[i].business_cards;
        for(var j=0; j<business_cards.length; j++){
          var tr = $('<tr>');
          tr.append($('<td>').html(orders[i].id));
          tr.append($('<td>').html(orders[i].user.gerencia));
          tr.append($('<td>').html((new Date(orders[i].updated_at)).toLocaleDateString() ));
          tr.append($('<td>').html(business_cards[j].nombre));
          tr.append($('<td>').html(business_cards[j].nombre_puesto));
          tr.append($('<td>').html(business_cards[j].email));
          tr.append($('<td>').html(business_cards[j].telefono));
          tr.append($('<td>').html(business_cards[j].celular));
          tr.append($('<td>').html(business_cards[j].web));
          tr.append($('<td>').html(business_cards[j].ccosto));
          tr.append($('<td>').html(business_cards[j].direccion));

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
