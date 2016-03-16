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
        <div class="col-xs-2">
          {{Form::select('inmueble',[null => 'TODOS LOS INMUEBLES']+Address::orderBy('inmueble')->lists('inmueble','inmueble'),null,['class' => 'form-control','placeholder' => ''])}}
        </div>

        <div class="col-xs-2">
          {{Form::text('codigo_postal',null,['class' => 'form-control','placeholder' => 'CODIGO POSTAL'])}}
        </div>

        <div class="col-xs-2">
          {{Form::text('calle',null,['class' => 'form-control','placeholder' => 'CALLE'])}}
        </div>


        <div class="col-xs-6">
  			  <button class="btn btn-primary btn-submit">
  			    <span class="glyphicon glyphicon-search"></span> BUSCAR
  			  </button>
            <a style="float:right;" href="{{action('AddressController@create')}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Añadir dirección</a>
        </div>

        
        
			</div>
		{{Form::close()}}
  </div>
  <br>
@if(count($users) == 0)
<div class="alert alert-warning">
  No se encontro ninguna dirección.
</div>
@else
  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
<!--             <th>
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
            </th> -->
            <th>
              GERENCIA
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
<!--             <td>
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
            	{{$user->address ? $user->address->inmueble : "N/A"}}
            </td>
      			<td>
      				  {{$user->address ? $user->address->domicilio : "N/A"}}
      			</td> -->
            <td>
              {{$user->gerencia}}
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