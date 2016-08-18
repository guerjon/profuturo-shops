<div class="modal fade" id="enable-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{{ Form::open(['method' => 'PUT'])}}
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Habilidar solicitud general</h4>
			</div>
			<div class="modal-body">
						¿Ésta seguro de recuperar esta solicitud general?
			</div>
			<input type="hidden" class="hidden" value="{{$active_tab}}" name="active_tab">
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-success">Confirmar</button>
			</div>
		</div>
	</div>
	{{ Form::close()}}
</div>

