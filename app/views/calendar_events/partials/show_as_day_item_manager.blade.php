@if($event->status == 3)
	<?php
		$color = '#57FE4B';
		$text = 'black';
	?>
@elseif($event->status == 2)
	<?php
		$color = '#F8FE4B';
		$text = 'black';
	?>
@elseif($event->status == 0 and $event->deliver_date->diffInDays($today) <= 2)
	<?php
		$color = '#FE4B4B';
		$text = 'white';
	?>
@else
	<?php
		$color = '#4D4D4D';
		$text = 'white';
	?>
@endif


<a href="#" style="background-color: {{$color}}; color:{{$text}}" class="list-group-item event-link detail-btn" data-toggle="modal"
   data-target="#request-modal" class="btn btn-sm btn-default detail-btn"
   data-request-id="{{$event->id}}" >
  <strong>{{ $event->project_title }}</strong>
  <br>

  {{$event->comments}}
</a>
