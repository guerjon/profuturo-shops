<a style="background-color:{{$event->manager ? ($event->manager->color ? $event->manager->color->color : 'white') : 'white'}}" href="#" class="list-group-item event-link detail-btn" data-toggle="modal"
   data-target="#request-modal" class="btn btn-sm btn-default detail-btn"
   data-request-id="{{$event->id}}">
  <strong>{{ $event->project_title }}</strong>
  <br>
  

  {{$event->comments}}
</a>
