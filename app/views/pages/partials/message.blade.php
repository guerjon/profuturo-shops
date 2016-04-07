@if(Auth::check())

	<div class="modal fade" role="dialog" id="message-modal" >
		<div class="modal-dialog modal-lg" style="z-index: 2147483647">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col col-xs-9">
							<h4 class="modal-title">Mensajes directos</h4>
						</div>
						<div class="col col-xs-1">
						    <a href="#" class="btn btn-primary" data-dismiss="modal" id="new-message">
	    						<span class="glyphicon glyphicon-plus" ></span> Mensaje nuevo
	  						</a>
						</div>
						<div class="col col-xs-2">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>	
					</div>
				</div>
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li role="presentation" class="">
					        <a href="#" aria-controls="admin" class="tabs message-type" data-type="recibidos" id="recibidos">
					        	Recibidos
					        </a>
      					</li>
					    <li role="presentation" class="active">
					        <a href="#" aria-controls="admin" class="tabs message-type" data-type="enviados" id="enviados">
					        	Enviados
					        </a>
      					</li>
					</ul>
				</div>
						
				<div class="modal-footer">
					<center>
    					<ul class="pagination_message" id="pagination_message"></ul>
  					</center>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	


	<div class="modal fade select_users_modal" id="select_users_modal"  role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="z-index: 2147483647">
		  <!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col col-xs-1">
							<a data-toggle="modal" data-dismiss="modal" data-target="#message-modal" >
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
						</div>
						<div class="col col-xs-10">
							<h4 class="modal-title text-center">Mensaje nuevo</h4>
						</div>
						<div class="col col-xs-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>	
					</div>
				</div>
				<div class="modal-body">
					{{Form::open(['action' => 'MessageController@store','method' => 'post','id' => 'post-message-modal-form'])}}
						<div class="form-group">
							@if(Auth::user()->role == 'admin')
								<div id="users-select"></div>
							@else
								<p>Este mensaje sera enviado al administrador.</p>
								{{Form::hidden('users[]',1,null)}}
							@endif

						</div>
						<div class="form-group">
							{{Form::textArea('mensaje',null,['class' => 'form-control','placeholder' =>  'Ingrese el mensaje','required'])}}	
						</div>
					{{Form::close()}}
				</div> 

				<div class="modal-footer">
					<button data-type="user" style="float:left" class="btn btn-default message-type" disabled>Mensaje por Usuario</button>
					<button data-type="divisional" style="float:left" class="btn btn-default message-type">Mensaje por divisional</button>
					<button data-type="region" style="float:left" class="btn btn-default message-type">Mensaje por región</button>
					<button id="post-message-modal-button" type="button" class="btn btn-default">Enviar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Resources Don´t erase -------------------------------------------------------------------------- !-->
	
	<table class="table table-responsive table-bordered" id="message-table">

	</table>
	
	{{Form::select('users[]',User::where('role','!=','admin')->lists('ccosto','id'),null,
		['id' => 'search-ccostos','class' => 'form-control js-example-basic-multiple ','multiple' => 'multiple','style' => 'width:100%','required'])
	}}


	


@endif




