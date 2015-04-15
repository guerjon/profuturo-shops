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
        Criticidad
      </th>
      <th>
        Fecha de solicitud
      </th>
      <th>
        Fecha de Inicio
      </th>
      <th>
        Fecha de entrega
      </th>
      <th>

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
      {{ $request->status_str}}
      </td>
      <td>
        {{ $request->unit_price * $request->quantity}}
      </td>
      <td>
       <div data-number="5" data-score="{{$request->rating}}" class="stars">
        
       </div> 
      </td>
      <td>
        {{$request->created_at->format('d-m-Y')}}
      </td>
      <td>
        {{$request->project_date->format('d-m-Y')}}
      </td>
      <td>
      {{$request->deliver_date}}
      </td>
      <td>
        <button data-toggle="modal" data-target="#request-modal" class="btn btn-sm btn-default detail-btn" data-request-id="{{$request->id}}">Detalles</button>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@include('general_requests.partials.show')

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
        $('input[name="request_id"]').val(info.id); 
        $('select[name="status"]').val(info.status);
      }
    });
  });
  $('.stars').raty({
  
  score: function() {
    return $(this).attr('data-score');
  },
  scoreName : 'rating',
    path : '/img/raty',
    readOnly: true
});
 $('#submit-btn').click(function(){
    $('#update-form').submit(); 
  });
});

</script>
@stop
