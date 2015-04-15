@extends('layouts.master')

@section('content')


<div class="calendar">

  <nav class="calendar-nav navbar navbar-default">
    <div class="container text-center">
      <ul class="navbar-nav nav navbar-left">
        <li><a href="?month={{$month['monthNum']}}&amp;year={{$month['yearNum']}}&amp;mod=prev"><span class="glyphicon glyphicon-chevron-left"></span> Anterior</a></li>
      </ul>

      <ul class="navbar-nav nav">
        <li><a>{{$month['monthName']}}</a></li>
      </ul>

      <ul class="navbar-nav nav navbar-right">
        <li><a href="?month={{$month['monthNum']}}&amp;year={{$month['yearNum']}}&amp;mod=next">Siguiente <span class="glyphicon glyphicon-chevron-right"></span></a></li>
      </ul>
    </div>
  </nav>

  @for($i=0; $i<6; $i++)
  <div class="row">

    @for($j=0; $j<7; $j++)
    @if($day = $month[(7*$i) + $j])

    <div class="col-xs-1 col-xs-calendar">
      <div class="panel panel-default panel-calendar
      {{ $day['enabled'] ? '' : 'panel-calendar-disabled'}}
      {{ $j % 7 != 0 ? '' : 'panel-calendar-holiday' }}">

      <div class="pull-right">

        @if(!$day['enabled'])
        <? $class= 'label-default' ?>
        @elseif($j% 7 == 0)
        <? $class='label-danger' ?>
        @else
        <? $make_link = true ?>
        <? $class="label-primary" ?>
        @endif

        @if(isset($make_link) and $make_link)
        <a href="{{action('AdminCalendarEventsController@getShow',[$month['yearNum'].'-'.$month['monthNum'].'-'.$day['number']])}}">
          <span class="label {{$class}}">{{$day['number']}}</span>
        </a>
        @else
        <span class="label {{$class}}">{{$day['number']}}</span>
        @endif

      </div>
      <div class="panel-body">

        

        <div class="list-group">
          @if($day['enabled'] and count($day['events']) > 0)
          @foreach($day['events'] as $event)
            @include('admin::calendar_events.partials.show_as_day_item', ['event' => $event])
          @endforeach

          @endif

        </div>
      </div>
    </div>
  </div>
  @endif
  @endfor
</div>
@endfor
</div>
@include('general_requests.partials.show')

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
@stop
