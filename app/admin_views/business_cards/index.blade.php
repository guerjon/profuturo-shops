@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="{{URL::previous()}}" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Tarjetas de Presentación</li>
  </ol>

<div class="text-right">
  <a href="{{action('AdminBusinessCardsController@create')}}" class="btn btn-primary">
    <span class="glyphicon glyphicon-import"></span> Importar archivo Excel
  </a>
</div>
<br>
    <div class="" style="margin: 20px inherit">
     <ul class="nav nav-tabs" role="tablist">
      
        <li role="presentation" class="{{$active_tab == 'untrashed' ? 'active' : ''}}">
          <a href="?active_tab=untrashed&page=1" class="tabs">
            Activos
          </a>
        </li>
      
        <li role="presentation" class="{{$active_tab == 'trashed' ? 'active' : ''}}">
          <a href="?active_tab=trashed&page=1" class="tabs">
            Inactivos
          </a>
        </li>

    </ul>
  </div>
<br>

@if($cards->count() == 0)

  <div class="alert alert-warning">
    No hay tarjetas que mostrar
  </div>
@else

  {{Form::open([
    'method' => 'GET',
    'class' => 'form-inline'
    ])}}
    <input type="hidden" value="{{$active_tab}}" name="active_tab">
  
    <div class="form-group">
      {{Form::number('no_emp', Input::get('no_emp'), ['class' => 'form-control', 'placeholder' => 'Numero de empleado'])}}
    </div>
    <div class="form-group">
      {{Form::number('ccosto', Input::get('ccosto'), ['class' => 'form-control', 'placeholder' => 'Centro de costos'])}}
    </div>


    <div class="form-group">
      {{Form::select('gerencia', [NULL => 'Todas las gerencias'] + $gerencias, Input::get('gerencia'), ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" >
        <span class="glyphicon glyphicon-filter"></span> Filtrar
      </button>
    </div>

  {{Form::close()}}
  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>
              No. empleado
            </th>
            <th>
              Nombre
            </th>
            <th>
              Puesto
            </th>
            <th>
              Centro de costos
            </th>
            <th>
              Gerencia
            </th>
            <th>
              Fecha de ingreso
            </th>
            <th>
              Inmueble
            </th>
            <th>

            </th>
          </tr>
        </thead>
        <tbody>
          @foreach($cards as $card)
          <tr>
            <td>
              {{$card->no_emp}}
            </td>
            <td>
              {{$card->nombre}}
            </td>
            <td>
              {{$card->nombre_puesto}}
            </td>
            <td>
              {{$card->ccosto}}
            </td>
            <td>
              {{$card->gerencia}}
            </td>
            <td>
              {{ $card->fecha_ingreso ? \Carbon\Carbon::createFromFormat('Y-m-d', $card->fecha_ingreso)->format('d/m/Y') : 'N/A'}}
            </td>
            <td>
              {{$card->inmueble}}
            </td>
            <td>
              {{Form::open([
                'class' => 'form-inline',
                'action' => ['AdminBusinessCardsController@destroy', $card->id],
                'method' => 'DELETE',
                ])}}

                @if($card->trashed())
                  <button type="submit" class="btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-ok"></span> Habilitar
                   </button>
                @else
                  <button type="submit" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove"></span> Inhabilitar
                  </button>
                @endif

              {{Form::close()}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center">
    {{ $cards->appends(Input::all())->links() }}
  </div>


@endif

@stop
