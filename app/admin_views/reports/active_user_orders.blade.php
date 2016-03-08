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


<div class="row">
  <div class="col-xs-10">
    {{Form::open([
      'id' => 'filter-form',
      'method' => 'GET',
      'action' => 'AdminReportsController@getActiveUserOrdersReport'
      ])}}
      <div class="col-xs-4">
        {{Form::selectMonth('month', \Carbon\Carbon::now('America/Mexico_City')->month, ['class' => 'form-control','id' => 'select_month'])}}
      </div>
      <div class="col-xs-2">
        {{Form::selectRange('year', \Carbon\Carbon::now('America/Mexico_City')->year - 5, \Carbon\Carbon::now('America/Mexico_City')->year, \Carbon\Carbon::now('America/Mexico_City')->year, ['class' => 'form-control','id' => 'select_year'])}}
      </div>

        <button class="btn btn-primary btn-submit" name="filter">
        <span class="glyphicon glyphicon-download-alt"></span> Filtrar
      </button>

    {{Form::close()}}    
  </div>
  <div class="col-xs-2">
    {{Form::open([
      'method' => 'GET',
      'action' => 'AdminReportsController@getActiveUserOrdersReportExcel',
      'id' => 'excel-form'
      ])}}
      
      <button class="btn btn-primary btn-submit" name="excel" id="button-excel">
        <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
      </button>
    {{Form::close()}}  
  </div>

</div>


<hr>

@if($products->count() == 0)

  <div class="alert alert-warning">
    No se encontraron
  </div>
@else

  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              ID
            </th>
            <th>
              CENTRO_DE_COSTOS
            </th>
            <th>  
              GERENCIA
            </th>
            <th>
                LINEA_NEGOCIO  
            </th>
            <th>
                CANTIDAD
            </th>
          </tr>

        </thead>
        <tbody>

          @foreach($products as $product)
          <tr>
            <td>  
                {{$product->id}}
            </td>
            <td>
                {{$product->ccosto}}  
            </td>
            <td>
              {{$product->gerencia}}
            </td>
            <td>  
            {{$product->linea_negocio}}
            </td>
            <td>  
            {{$product->quantity}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <center>  
        {{$products->links()}}
    </center>
    
  </div>
@endif
@stop

@section('script')
    <script >
      $(function(){
          $('#button-excel').click(function(event){
            event.preventDefault();
            $select_month = $('#select_month').clone();
            $select_month.hide().removeAttr('id');
            $('#excel-form').append($select_month);
            $select_year = $('#select_year').clone();
            $select_year.hide().removeAttr('id');
            $('#excel-form').append($select_year);
            $('#excel-form').submit();
          });
      });

    </script>
@endsection