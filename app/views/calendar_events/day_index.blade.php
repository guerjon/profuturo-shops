@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-sm-8 col-sm-offset-2">

    <div class="page-header">
      <h3>Eventos del día {{$date}}</h3>
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