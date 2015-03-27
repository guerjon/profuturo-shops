@extends('layouts.master')

@section('content')

@if($requests->count() > 0)

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
        Asesor asignado
      </th>
      <th>
        Asignar asesor
      </th>
    </tr>
  </thead>
  <tbody>
    @foreach($requests as $request)
    <tr>
      <td>
        {{$request->id}}
      </td>
      <td>
        {{$request->project_title}}
      </td>
      <td>
        Pendiente
      </td>
      <td>
        {{ $request->unit_price * $request->quantity}}
      </td>
      <td>

      </td>
      <td>
        <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default detail-btn" data-request-id="{{$request->id}}">Asignar</button>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@include('admin::general_requests_manager.show')

@else

<div class="alert alert-info">
  No se han hecho solicitudes nuevas.
</div>
@endif

@stop

@section('script')
<script>
$(function(){
  $('.detail-btn').click(function(){
    $.get('/api/request-info/' + $(this).attr('data-request-id'), function(data){
      if(data.status == 200){
        var info = data.request;
        for(key in info){
          $('#request-' + key).text(info[key]);
        }
      }
    });
  });
})
</script>
@stop
