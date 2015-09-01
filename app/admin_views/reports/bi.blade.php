@extends('layouts.master')

@section('content')

<div class="page-header">
  <h3>Reporte de productos</h3>
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
    {{Form::label('ccosto','Ccosto')}}
    {{Form::text('ccosto',null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto'])}}
  </div>
  <div class="col-xs-2">
    {{Form::label('category_id','Categoria')}}
    {{Form::select('category_id',[null => 'Seleccione una categoria'] + $categories,null,['class' => 'form-control'])}}
  </div>

  <div class="col-xs-2">
    {{Form::label('product_id','Producto')}}
    {{Form::select('product_id',[null => 'Seleccione un producto'] +$products,null,['class' => 'form-control'])}}
  </div>
  <div class="col-xs-2">
    {{Form::label('since','Desde')}}
    <input type="date" name="since" placeholder="Desde" class ="form-control">
  </div>
  <div class="col-xs-2">
    {{Form::label('until','Hasta')}}
    <input type="date" name="until" placeholder="Hasta" class = "form-control">
  </div>
  
  {{--   {{ Form::submit('Descargar excel', ['class' => 'btn btn-warning btn-submit'])}}
   --}}
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
  $.get('/admin/api/bi-report', $('#filter-form').serialize(), function(data){
    $('.table tbody').empty();
    if(data.status == 200){
      console.log(data.report);
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

      for(var i=0; i<headers.length; i++){
        $('.table thead tr').append($('<th>').html(headers[i]));
      }
      for(var i=0; i<report.length; i++){
        var tr = $('<tr>');

        for(var j=0; j<headers.length; j++){
          tr.append($('<td>').html(report[i][headers[j]]));
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
