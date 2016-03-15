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
		{{Form::open([
		  'id' => 'filter-form',
		  'method' => 'GET',
		  'action' => 'AddressController@index',
		  ])}}
			<div class="row" style="margin:1%">
			  <div class="col-xs-2 col-xs-offset-1">
			    {{Form::text('ccostos',null,['class' => 'form-control','placeholder' => 'CCOSTO'])}}
			  </div>

			  <div class="col-xs-2" >
			    {{Form::select('regional',Region::lists('name','id'),null,['class' => 'form-control','placeholder' => 'REGIONAL'])}}
			  </div>

			  <div class="col-xs-2">
			    {{Form::select('divisional',[null => 'Todas las divisionales']+Divisional::lists('name','id'),null,['class' => 'form-control','placeholder' => 'DIVISIONAL'])}}
			  </div>

        <div class="col-xs-2">
          {{Form::select('linea_de_negocio',['AFORE' => 'AFORE','FONDOS' => 'FONDOS','PENSION' => 'PENSIÓN'],null,['class' => 'form-control','placeholder' => 'INMUEBLE'])}}
        </div>

			  <button class="btn btn-primary btn-submit">
			    <span class="glyphicon glyphicon-search"></span> BUSCAR
			  </button>

			</div>
		{{Form::close()}}
  </div>
  <br>
@if(count($users) == 0)
<div class="alert alert-warning">
  Actualmente no hay direcciones.
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
            	DIVISIONAL
            </th>
            <th>
              REGIONAL
            </th>
            <th>
              LINEA DE NEGOCIO
            </th>
            <th>
              INMUEBLE
            </th>
            <th>
              DOMICILIO	
            </th>
            <th>
            	
            </th>
          </tr>
        </thead>

        <tbody>
          @foreach($users as $user)
          <tr>
            <td>
            	{{$user->ccosto}}
            </td>
            <td>
            	{{$user->gerencia}}
            </td>
            <td>
            	{{$user->divisional ? $user->divisional->name : "N/A"}}
            </td>
            <td>
            	{{$user->region ? $user->region->name : "N/A"}}
            </td>
            <td>
            	{{$user->linea_negocio}}
            </td>
            <td>
            	{{$user->inmueble}}
            </td>
      			<td>
      				  {{$user->domicilio}}
      			</td>
      			<td>
      				  <div class="btn-group">
      				      
      				        <a href="{{action('AddressController@edit', $user->id)}}" class="btn btn-warning btn-xs" style="height:25px;margin:2px;padding-top:2px;" >
      				          <span class="glyphicon glyphicon-pencil" style="padding-top:2px;"></span> Editar
      				        </a>

      				  </div>
      			</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif


@stop