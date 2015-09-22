@extends('layouts.master')

@section('content')

<ol class="breadcrumb">
    <a href="#" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="/">Inicio</a></li>
    <li class="active">Asignación de solicitudes</li>
  </ol>

@if($requests->count() > 0)

<h3>Asignación de solicitudes generales</h3>

<div class="container">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>
          # de sol.
        </th>
        <th>
          Título proyecto
        </th>
        <th>
          Estatus
        </th>
        <th>
          Presupuesto
        </th>
        <th>
          Asignado
        </th>
        <th class="text-center">
          Asignar asesor
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($requests as $request)
      <tr class="{{$request->manager?'info':''}}">
        <td>
          {{$request->id}}
        </td>
        <td>
          {{$request->project_title}}
        </td>
        <td>
          {{$request->getStatusStrAttribute()}}
        </td>

        <td>
          {{money_format("%.2n",$request->unit_price * $request->quantity)}}
        </td>
        <td>
          @if($request->manager != null)
              <span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span>
          @else
              <span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span>
          @endif
        </td>
        <td class="text-center">
          @if($request->manager != null)
          <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default assign-btn" data-request-id="{{$request->id}}">Reasignar</button>
          <p class="text-muted"><small>Asignado a: {{$request->manager->gerencia}}</small></p>
          @else
          <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default assign-btn" data-request-id="{{$request->id}}">Asignar</button>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@include('admin::general_requests_assign.partials.assign_modal')

@else

<div class="alert alert-info">
  No se han hecho solicitudes nuevas.
</div>
@endif

@stop

@section('script')
<script>
$(function(){

  $('.assign-btn').click(function(){
    $('#request_id').val($(this).attr('data-request-id'));
  });

  $('#submit-btn').click(function(){
    $('#assign-form').submit();
  });

  $('.rating-raty').raty({
    scoreName : 'rating',
    path : '/img/raty'
  });


})
</script>
@stop
