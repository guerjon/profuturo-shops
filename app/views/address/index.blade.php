@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li class="active">Direcciones</li>
</ol>

  <br>
    <div class="row">
      <h1>Direcciones</h1>
    </div>
  	<div class="row">
		{{Form::open([
		  'id' => 'filter-form',
		  'method' => 'GET',
		  'action' => 'AddressController@index',
		  ])}}
			<div class="row" style="margin:1%">
        <div class="col-xs-2">
          {{Form::text('ccostos',Input::get('ccostos'),['class' => 'form-control','placeholder' => 'CCOSTOS'])}}
        </div>
        <div class="col-xs-2">
          {{Form::select('divisional_id',[null => "TODAS LAS DIVISIONALES"] + Divisional::orderBy('name')->lists('name','id'),Input::get('divisional_id'),['class' => 'form-control'])}}
        </div>
        
        <div class="col-xs-2">
          {{Form::select('region_id',[null => "TODAS LAS REGIONES"] + Region::orderBy('name')->lists('name','id'),Input::get('region_id'),['class' => 'form-control'])}}
        </div>

        <div class="col-xs-2">
          {{Form::select('gerencia',[null => 'TODAS LAS GERENCIAS']+ Address::orderBy('gerencia')->lists('gerencia','gerencia'),null,['class' => 'form-control'])}}
        </div>

        <div class="col-xs-2">
          {{Form::select('inmueble',[null => 'TODOS LOS INMUEBLES']+ Address::orderBy('inmueble')->lists('inmueble','inmueble'),null,['class' => 'form-control'])}}
        </div>

        <div class="col-xs-2">
          {{Form::text('domicilio',Input::get('domicilio'),['class' => 'form-control','placeholder' => 'DOMICILIO'])}}
        </div>
 
			</div>
      <br>
      <div class="row text-center">
        
          <button class="btn btn-primary btn-submit" id="search-btn" type="button">
            <span class="glyphicon glyphicon-search"></span> BUSCAR
          </button>
          <a href="{{action('AddressController@create')}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>   Añadir dirección
          </a>
          <button type="button" class="btn btn-primary" id="download-btn">
            <span class="fa fa-download"></span> Descargar
          </button>
          <a href="{{action("AddressController@index")}}" class="btn btn-default">
            <span class="fa fa-eraser"></span> Borrar filtros
          </a>
        
      </div>
        
		{{Form::close()}}
  </div>
  <hr>
@if(count($addresses) == 0)
<div class="alert alert-warning">
  No se encontro ninguna dirección.
</div>
@else
  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              CCOSTOS
            </th>
            <th>
              GERENCIA
            </th>
            <th>
              INMUEBLE
            </th>
            <th style="max-width: 200px">
              DOMICILIO	
            </th>
            <th style="max-width: 200px">
              DOMICILIO ALTERNATIVO
            </th>
            <th>
              DIVISIONAL
            </th>
            <th>
            	REGIÓN
            </th>
          </tr>
        </thead>

        <tbody>
          @foreach($addresses as $address)
          <tr>
            <td>
              {{$address->ccostos}}
            </td>
            <td>
              {{$address->gerencia}}
            </td>
            <td>
              {{$address->inmueble}}
            </td>
      			<td style="max-width: 200px">
              {{$address->domicilio}}
            </td>
            <td style="max-width: 200px">
              {{$address->posible_cambio}}
            </td>
            <td>
              {{$address->divisional ? $address->divisional->name : 'N/A'}}
            </td>
            <td>
              {{$address->region ? $address->region->name : 'N/A'}}
            </td>
            <td>
      				  <div class="btn-group">
      				        <a href="{{action('AddressController@edit', $address->id)}}" class="btn btn-warning btn-xs" style="height:25px;margin:2px;padding-top:2px;" >
      				          <span class="glyphicon glyphicon-pencil" style="padding-top:2px;"></span> Editar
      				        </a>
      				  </div>
      			</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="text-center">
      {{$addresses->appends(Input::except('page'))->links();}}
    </div>
  </div>
@endif


@stop

@section('script')

  <script type="text/javascript">
    $(function(){
        $('#download-btn').click(function(){
          $('#filter-form').append('<input class="hide" type="hide" id="excel" name="excel" value="1"> ')
          $('#filter-form').submit();
        });
        $('#search-btn').click(function(){
          $('#excel').remove();
          $('#filter-form').submit();
        });
    });
  </script>
@endsection