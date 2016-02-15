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

<div class="text-right">
  <a href="{{action('AdminAddressController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-plus"></span> Agregar nueva dirección
  </a>
</div>

  <br>
  <br>
@if(count($addresses) == 0)
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
            	{{$address->regional}}
            </td>
            <td>
            	{{$address->linea_de_negocio}}
            </td>
            <td>
            	{{$address->inmueble}}
            </td>
			<td>
				{{$address->domicilio}}
			</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif


@stop