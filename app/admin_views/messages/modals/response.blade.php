<!-- Modal -->
{{Form::open(['action' => 'AdminMessagesController@store'])}}
	<div id="response-modal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Responder mensaje a: <span id='user-name'></span></h4>
				</div>
				<div class="modal-body">
					{{Form::label('body','Mensaje',['class' => 'label-control'])}}
					{{Form::textArea('body',null,['class' => 'form-control'])}}			
				</div>
				<input type="hidden" name="receivers[]" id="hidden-receivers">
				<input type="hidden" name="active_tab" value="users">
				<div class="modal-footer">
					<center>
						<button class="btn btn-primary btn-lg">
							Enviar
						</button>						
					</center>
				</div>
			</div>
		</div>
	</div>
{{Form::close()}}