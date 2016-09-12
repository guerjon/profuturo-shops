@extends('layouts.master')

@section('content')
	<br>
	<div class="container">
		<div class="row">
			<div class="col-xs-2">
				<h3>Mensajes</h3>	
			</div>
			<div class="col-xs-8"></div>
			<div class="col-xs-2">
				<a class="btn btn-primary" href="{{action('AdminMessagesController@create')}}">
					<span class="fa fa-plus"></span>
					Nuevo mensaje
				</a>
			</div>
			<hr>
		</div>
		<div class="row">
    	<ul class="nav nav-tabs" role="tablist">
	      	<li role="presentation" class="{{$active_tab == 'received' ? 'active' : ''}}">
	        	<a href="?active_tab=received&page=1" class="tabs">
	          		Recibidos
	        	</a>
	      	</li>			
	      	<li role="presentation" class="{{$active_tab == 'delivered' ? 'active' : ''}}">
	        	<a href="?active_tab=delivered&page=1" class="tabs">
	          		Enviados
	        	</a>
	      	</li>			
	    </ul>
		</div>
		<div class="row">
			<table class="table table-striped">
				<thead>
					<th>
						Mensaje
					</th>
					<th>
						{{$active_tab == 'received' ? 'Fecha recibido' : 'Fecha de envio'}}
					</th>

					@if($active_tab == 'received')
						<th>
							
						</th>
					@endif

				</thead>

				<tbody>
					@foreach($messages as $message)
						<tr>

							<td>
								<mark>
									{{$message->body}}	
								</mark>
							</td>
							<td>
								{{$message->created_at}}
							</td>
							@if($active_tab == 'received')	
								<td>
									<button class="btn btn-default btn-response" data-user-id="{{$message->sender_id}}" data-user-name="{{User::find($message->sender_id)->gerencia}}">
										<span class="fa fa-reply"></span>
										Responder
									</button>
								</td>
							@endif							
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="row text-center">
			{{$messages->appends(Input::except('page'))->links()}}
		</div>
	</div>
	@include('messages/modals/response')
@endsection


@section('script')
	<script>
		$(function(){
			$('.btn-response').click(function(){

				var modal = $('#response-modal');
				var id = $(this).attr('data-user-id');
				var name = $(this).attr('data-user-name');

				modal.find('#user-name').html(name);
				modal.find('#hidden-receivers').val(id);
				modal.modal();
			});
		});
	</script>
@endsection