@extends('layouts.master')

@section('content')

  <ol class="breadcrumb">
    <a href="#" class="back-btn">
      <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
      &nbsp;&nbsp;&nbsp;
    <li><a href="#">Inicio</a></li>
    <li><a href="#">Agenda</a></li>
    <li class="active">Día de Agenda</li>
  </ol>

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    <div class="page-header">
      <h3 style="text-align:center">Eventos del día {{$date}}</h3>
    </div>
    @if($events->count() == 0)    
    <div class="alert alert-warning">
      Sin eventos que mostrar para este día
    </div>
    @else
    <div class="list-group">
      @foreach($events as $event)
      @include('calendar_events.partials.show_as_day_item', ['event' => $event])
      @endforeach
    </div>
    @endif
  </div>
</div>

@stop