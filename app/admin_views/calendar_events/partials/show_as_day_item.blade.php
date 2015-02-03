<a href="#" class="list-group-item event-link" data-event-id="{{$event->id}}">
  <strong>{{ $event->datetime->format('H:i') }}</strong>
  <br>

  {{$event->comments}}
</a>
