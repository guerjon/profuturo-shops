<a stye="background-color:{{$event->user->color ? $event->user->color->color : 'white'}}" href="#" class="list-group-item event-link detail-btn" data-toggle="modal"
   data-target="#request-modal" class="btn btn-sm btn-default detail-btn"
   data-request-id="{{$event->id}}">
  <strong>{{ $event->project_title }}</strong>
  <br>
  {{$event->user}}
  {{$event->comments}}


</a>
