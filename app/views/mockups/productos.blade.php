@extends('layouts.master')

@section('content')

<ul class="nav nav-pills">
  <li role="presentation" class="active"><a href="#">Productos</a></li>
  <li role="presentation"><a href="pedidos">Mis pedidos</a></li>
</ul>

<div class="pull-right">
  <form class="form-inline">
    <label>Buscar</label>
    <input type="text" class="form-control">
  </form>
</div>

<br>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Todos</a></li>
  <li role="presentation"><a href="#">Oficina</a></li>
  <li role="presentation"><a href="#">Móviles</a></li>
  <li role="presentation"><a href="#">Impresión</a></li>
  <li role="presentation"><a href="#">Orden</a></li>
</ul>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Descripción</th>
        <th>Imagen</th>
        <th>Cantidad</th>
        <th>Selección</th>
      </tr>
    </thead>

    <tbody>
      @for($i=0; $i<15; $i++)
      <tr>
        <td>Producto {{$i}}</td>
        <td><img src="http://lorempixel.com/40/40/?nada={{rand()}}" class="img-thumbnail"></td>
        <td>{{Form::select('cantidad', [1,2,3,4,5], NULL, ['class' => 'form-control'])}}</td>
        <td><a href="#">Seleccionar</a></td>
      </tr>
      @endfor
    </tbody>
  </table>
</div>

<div class="text-center">
  <nav>
    <ul class="pagination">
      <li><a href="#"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>
      <li class="active"><a href="#">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li><a href="#">5</a></li>
      <li><a href="#"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>
    </ul>
  </nav>
</div>

@stop
