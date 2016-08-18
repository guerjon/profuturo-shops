@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
  <a href="{{URL::previous()}}" class="back-btn">
    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
  </a>
    &nbsp;&nbsp;&nbsp;
  <li><a href="/">Inicio</a></li>
  <li><a href="/admin/reports/index">Reportes</a></li>
  <li class="active">Pedidos tarjetas</li>
</ol>

<div class="page-header">
  <h3>Reporte de pedidos tarjetas</h3>
</div>

{{Form::open([
  'id' => 'filter-form',
  'method' => 'GET',
  'action' => 'AdminReportsController@getBcOrdersReport'
  ])}}
    {{Form::hidden('page',null,['id' => 'number_page'])}}
  <div class="row">
    <div class="col-xs-3">
      {{Form::label('since','DESDE')}}
      {{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
      <br>
      {{Form::label('until','HASTA')}}
      {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
  </div>

  <div class="col-xs-3">
    {{Form::label('type','TIPO DE TARJETAS')}}

    {{Form::label('divisional_id','DIVISIONAL')}}
    {{Form::select('divisional_id',[null => "Seleccione una divisional"] + $divisionals,null,['class' => 'form-control','placeholder' => 'Ingrese un ccosto','id' => 'ccosto'])}}
    <br>
    {{Form::label('region_id','REGIÓN ')}}
    {{Form::select('region_id',[null => "Seleccione una región"]+$regions,null,['class' => 'form-control','placeholder' => 'Ingrese la región','id' => 'region_id' ])}}
    </div>
  <div class="col-xs-3">
    {{Form::label('num_pedido','NUM_PEDIDO')}}
    {{Form::text('num_pedido',null,['class' => 'form-control','placeholder' => 'Ingrese el numero de pedido','id' => 'num_pedido' ])}}
    <br>
  </div>
  <div class="col-xs-2 text-right">
    <div class="row">
      <div class="col-xs-6">
        <button class="btn btn-primary" type="submit">
            <i class="glyphicon glyphicon-search"></i>
            Filtrar
        </button>      
      </div>
      <div class="col-xs-6">      
        <button class="btn btn-primary btn-submit" id="download-btn-excel" type="button">
            <span class="glyphicon glyphicon-download-alt"></span> Descargar excel
        </button>
      </div>
    </div>  
  </div>

</div>
    <input type="hidden" name="active_tab" value="{{$active_tab}}">
{{Form::close()}}

<hr>

<div class="container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="{{$active_tab == 'tarjetas_presentacion' ? 'active' : ''}}">
          <a href="?active_tab=tarjetas_presentacion&page=1" aria-controls="" class="tabs">
            Tarjetas de presentación
          </a>
        </li>
        <li role="presentation" class="{{$active_tab == 'tarjetas_blancas' ? 'active' : ''}}">
          <a href="?active_tab=tarjetas_blancas&page=1" aria-controls="" class="tabs">
            Tarjetas blancas
          </a>
        </li>
        <li role="presentation" class="{{$active_tab == 'atraccion_talento' ? 'active' : ''}}">
          <a href="?active_tab=atraccion_talento&page=1" aria-controls="" class="tabs">
            Atracción de talento
          </a>
        </li>
        <li role="presentation" class="{{$active_tab == 'gerente_comercial' ? 'active' : ''}}">
          <a href="?active_tab=gerente_comercial&page=1" aria-controls="" class="tabs">
            Gerente comercial
          </a>
        </li>
    </ul>

    @if(sizeof($bc_orders) < 1 )
        <div class="alert alert-info">No se encontraron reportes.</div>
    @else

        <div class="table-responsive">
            <table class="table table-responsive">
              <thead>
                <tr>
                    <th>
                        FECHA_PEDIDO
                    </th>
                    <th>
                        NUM_PEDIDO
                    </th>
                    <th>
                        CANTIDAD
                    </th>
                    <th>
                        GERENCIA
                    </th>
                    <th>
                        FECHA
                    </th>
                    <th>
                        NOMBRE_PUESTO
                    </th>
                    <th>
                        EMAIL
                    </th>
                    <th>
                        TELEFONO
                    </th>
                    <th>
                        CELULAR
                    </th>
                    <th>
                        WEB
                    </th>
                    <th>
                        DIRECCIÓN
                    </th>
                    <th>
                      INMUEBLE
                    </th>

                    <th>
                        DIRECCIÓN_ALTERNATIVA
                    </th>
                    @if($active_tab == 'atraccion_talento' || $active_tab == 'gerente_comercial')
                        <th>
                            PUESTO_ATRACCION_GERENTE
                        </th>
                    @endif
                    <th>
                        ESTATUS
                    </th>
                </tr>
              </thead>

              <tbody>
                    @foreach($bc_orders as $bc_order)
                        <tr>
                            <td>
                                {{$bc_order->FECHA_PEDIDO}}
                            </td>
                            <td>
                                {{$bc_order->NUM_PEDIDO}}
                            </td>
                            <td>
                                {{$bc_order->CANTIDAD}}
                            </td>
                            <td>
                                {{$bc_order->GERENCIA}}
                            </td>
                            <td>
                                {{$bc_order->FECHA}}
                            </td>
                            <td>
                                {{$bc_order->NOMBRE_PUESTO}}
                            </td>
                            <td>
                                {{$bc_order->EMAIL}}
                            </td>
                            <td>
                                {{$bc_order->TELEFONO}}
                            </td>
                            <td>
                                {{$bc_order->CELULAR}}
                            </td>
                            <td>
                                {{$bc_order->WEB}}
                            </td>
                            <td>
                                {{$bc_order->DIRECCION}}
                            </td>
                            <td>
                              {{$bc_order->INMUEBLE}}
                            </td>
                            <td>
                                {{$bc_order->DIRECCION_ALTERNATIVA}}
                            </td>
                            @if($active_tab =='atraccion_talento' || $active_tab == 'gerente_comercial')
                                <td>
                                    {{$bc_order->PUESTO_ATRACCION_GERENTE}}
                                </td>
                            @endif
                            <td>
                                {{$bc_order->ESTATUS}}
                            </td>

                        </tr>
                    @endforeach
              </tbody>
            </table>
        </div>
        
         <center>
            {{$bc_orders->appends(Input::except('page'))->links()}}
        </center>
    @endif
</div>

   

@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            $('#download-btn-excel').click(function(){
                $('#filter-form').append('<input value="1" type="hidden" name="excel">')
                $('#filter-form').submit();
            });
        });
    </script>
@endsection