<!-- Modals ------------------------------------------ -->

{{-- <div class="modal fade preview-modal" data-backdrop="" id="preview-modal"  role="dialog" aria-labelledby="preview-modal" aria-hidden="true">
	<div class="modal-dialog">
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col col-xs-8">
						<h4 class="modal-title">Mensajes directos</h4>
					</div>
					<div class="col col-xs-2">
					    <a href="{{action('MessageController@create')}}" class="btn btn-primary btn-sm">
    						<span class="glyphicon glyphicon-plus"></span> Mensaje nuevo
  						</a>
					</div>
					<div class="col col-xs-2">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>	
				</div>
				
			</div>
			<div class="modal-body">
				<ul class="message-lists">

				</ul>
			</div>
			<div class="modal-footer">
				<button data-toggle="modal" class="btn btn-primary" data-target="select_users_modal"  data-dismiss="modal">Siguiente</button>
			</div>
		</div>
	</div>
</div> --}}



<div class="modal fade" role="dialog" id="myModal" style="z-index:100">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade select_users_modal" data-backdrop="" id="select_users_modal"  role="dialog" aria-labelledby="select_users_modal" aria-hidden="true">
	<div class="modal-dialog">
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col col-xs-1"><span class="glyphicon glyphicon-chevron-left"></span></div>
					<div class="col col-xs-10">
						<h4 class="modal-title">Mensaje nuevo</h4>
					</div>
					<div class="col col-xs-1">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>	
				</div>
				
			</div>
			<div class="modal-body">
				<textarea name="select_users" id="select_users" cols="30" rows="10"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

