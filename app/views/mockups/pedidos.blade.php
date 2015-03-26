@extends('layouts.master')

@section('content')

<ul class="nav nav-pills">
  <li role="presentation"><a href="productos">Productos</a></li>
  <li role="presentation" class="active"><a href="#">Mis pedidos</a></li>
</ul>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. de pedido</th>
        <th>Fecha de pedido</th>
        <th>Entregado</th>
        <th>Detalle de entrega</th>
      </tr>
    </thead>

    <tbody>
      @for($i=0; $i<5; $i++)
      <tr>
        <td><a href="#" data-target="#myModal" data-toggle="modal">{{($i+1)*rand()}}</a></td>
        <td>{{(new Datetime)->format('d/M/Y')}}</td>
        <td>
          @if($i % 2 == 0)
            <span class="glyphicon glyphicon-ok"></span>
          @else
            <span class="glyphicon glyphicon-remove"></span>
          @endif
        </td>
        <td>
          Pendiente
        </td>
      </tr>
      @endfor
    </tbody>
  </table>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">No de pedido XXXXX</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estatus</th>
              </tr>
            </thead>

            <tbody>
              @for($i=0; $i<10; $i++)
              <tr>
                <td>Producto XXX</td>
                <td>3</td>
                <td>{{(new Datetime)->format('d/M/Y')}}</td>
                <td>{{Form::select('nnn', ['Incompleto', 'Completo'], NULL, ['class' => 'form-control'])}}</td>
              </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <div class="pull-left">
          {{Form::textarea('nevermind', NULL, ['class' => 'form-control', 'style' => 'max-width: 300px;', 'rows' => 2])}}
        </div>
        <label class="checbox-inline">
          <input type="checkbox"> Entregado
        </label>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Guardar</button>
      </div>
    </div>
  </div>
</div>

@stop
