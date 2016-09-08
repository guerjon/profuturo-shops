@extends('layouts.master')

@section('content')

	<div class="container-fluid">
		<ol class="breadcrumb">
			<a href="{{URL::previous()}}" class="back-btn">
				<span class="glyphicon glyphicon-arrow-left"></span> Regresar
			</a>
				&nbsp;&nbsp;&nbsp;
			<li>
				<a href="/">Inicio</a>
			</li>
			<li class="active">
				Solicitudes Generales
			</li>
		</ol>

		<br>
		@if(Auth::user()->is_admin)
			{{Form::open([
				'id' => 'filter-form',
				'method' => 'GET',
			])}}
				<div class="row">
			
					<div class="col-xs-2">
						USUARIO DE PROYECTOS
						{{Form::select('user_id',[null=>'Todos']+$users,Input::get('user_id'),['class' => 'form-control'])}}
					</div>
			 
					<div class="col-xs-3 ">
						FECHA DE SOLICITUD DESDE:
						{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
						</div>
					<div class="col-xs-3 ">
						FECHA DE SOLICITUD HASTA:
						{{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
					</div>					
					<div class="col col-xs-2">
						<br>
						<a href="{{action('AdminApiController@getGeneralRequest', ['excel'=>'excel'])}}" class="btn btn-primary btn-submit" style="float:right">
								<span class="glyphicon glyphicon-download-alt"></span> EXCEL
							</a>  
					</div>
					
					<div class="col col-xs-2">
						<input type="text" class="hidden" value="{{$active_tab}}" name="active_tab">  
						<br>
						<button type="submit" class="btn btn-primary">
					 		<span class="glyphicon glyphicon-filter"></span> Filtrar
						</button>
					</div>
				</div>
				<hr>
			{{Form::close()}}
		@endif

		<ul class="nav nav-tabs">
			<li role="presentation" class="{{$active_tab == 'all' ?  'active' : ''}}">
				<a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'all'])}}">Todas</a> 
			</li>
			<li role="presentation" class="{{$active_tab == 'assigned' ?  'active' : ''}}">
				<a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'assigned'])}}">Asignadas</a> 
			</li>
			<li role="presentation" class="{{$active_tab == 'not_assigned' ? 'active' : ''}}">
				<a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'not_assigned'])}}">No asignadas</a> 
			</li> 
			<li role="presentation" class="{{$active_tab == 'deleted_assigned' ? 'active' : ''}}">
				<a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'deleted_assigned'])}}">Canceladas asignadas</a>
			</li>
			<li role="presentation" class="{{$active_tab == 'deleted_unassigned' ? 'active' : ''}}">
				<a href="{{action('AdminGeneralRequestsController@index',['active_tab' =>'deleted_unassigned'])}}">Canceladas no asignadas</a>
			</li>
		</ul>

		@if($requests->count() > 0)

			<div class="container-fluid">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>
								# de sol.
							</th>
							<th>
								Título proyecto
							</th>
							<th>
								Estatus
							</th>
							<th>
								Presupuesto
							</th>
							<th>
								Criticidad
							</th>
							<th>
								Fecha de solicitud
							</th>
							<th>
								Fecha de Inicio
							</th>
							<th>
								Fecha de entrega
							</th>
							<th>
								Línea de negocio
							</th>
							<th>
								Acciones
							</th>
							@if($active_tab == 'assigned' || $active_tab == 'all' || $active_tab == 'deleted_assigned')
								<th class="text-center">
									Asesor
								</th>
							@endif	
						</tr>
					</thead>
					<tbody>
						@foreach($requests as $request)
						<tr>
							<td>
								
							{{link_to_action('AdminGeneralRequestsController@show',$request->solicitud,['id' =>$request->solicitud]),null}}   

							</td>
							<td>
								{{$request->titulo}}
							</td>
							<td>
								{{$request->estatus}}
							</td>
							<td>
								{{$request->presupuesto}}
							</td>
							<td>
							 <div data-number="5" data-score="{{$request->rating}}" class="stars">
								
							 </div> 
							</td>
							<td>
								{{substr($request->creada, 0,-8) }} 
							</td>
							<td>
								{{ \Carbon\Carbon::createFromFormat('Y-m-d 00:00:00',$request->project_date)->format('Y-m-d')}}
							</td>
							<td>
								{{$request->deliver_date->format('Y-m-d')}}
							</td>
							<td>
								{{$request->linea_negocio}}
							</td>							
							<th>
								<div class="dropdown">
								  	<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Acciones
								  	<span class="fa fa-cog"></span></button>
								  	<ul class="dropdown-menu">
								  		@if($active_tab != 'deleted_unassigned' && $active_tab != 'deleted_assigned')
											@if($active_tab == 'assigned' )
												<li>
													<a data-toggle="modal" class="assign-btn" data-request-id="{{$request->id}}">
														<i class="fa fa-retweet" aria-hidden="true"></i>
														Reasignar
													</a>
												</li>
											@else
												<li>
													<a data-toggle="modal" class="assign-btn" data-request-id="{{$request->id}}">
														<span class="fa {{$request->manager_id ? 'fa-retweet' : 'fa-plus'}}"></span>
														{{$request->manager_id ? 'Reasignar' : 'Asignar'}}
														
													</a>
												</li>
											@endif
										@endif
										<li>
											@if($active_tab == 'deleted_assigned' || $active_tab == 'deleted_unassigned')

												<a type="submit" class="btn-enable" data-request-id="{{$request->solicitud}}">
														<span class="glyphicon glyphicon-ok"></span> Habilitar
													</a>
											@else
												<a  type="submit" class="btn-disable" data-request-id="{{$request->solicitud}}">
													<span class="fa fa-times"></span>
													Eliminar		
												</a>
											@endif
										</li>
								  	</ul>
								</div>
							</th>
							@if($active_tab == 'assigned' || $active_tab == 'all' || $active_tab == 'deleted_assigned')
								<th>
									{{User::find($request->manager_id) ? User::find($request->manager_id)->gerencia : 'Sin asesor'}}
								</th>
							@endif
						</tr>

						@endforeach
					</tbody>
				</table>
			</div>

			
			@include('admin::general_requests.partials.enable')
			@include('admin::general_requests.partials.disable')
			@include('admin::general_requests.partials.assign_modal')

			<div class="text-center">
				{{$requests->appends(Input::except('page'))->links()}}
			</div>
		@else
			<br>
			<div class="alert alert-info">
				No se han hecho solicitudes nuevas.
			</div>
		@endif
	</div>
@stop

@section('script')
<script>
$(function(){
	$('.detail-btn').click(function(){

		$.get('/api/request-info/' + $(this).attr('data-request-id'), function(data){
			if(data.status == 200){
				
				var info = data.request;
				for(key in info){
					console.log(key);            
					if(key == 'project_date')
						$('#request-' + key).text(info[key].slice(0,-9));
					else if(key == 'deliver_date')
						$('#request-' + key).text(info[key].slice(0,-9));
					else
						$('#request-' + key).text(info[key]);         
				}
				$('input[name="request_id"]').val(info.id); 

				var estatus = ['Acabo de recibir tu solicitud, en breve me comunicare contigo',
										 'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas',
										 'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados',
										 'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio',
										 'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad',
										 'Conforme a tu elección…, ingresa tu solicitud en People Soft',
										 'Ya se envió la orden de compra al proveedor',
										 '','Tu pedido llego en excelentes condiciones, en el domicilio… y recibió…',
										 'Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción.'];
				var info_status = parseInt(info.status);
				
				$("#status").empty();
				for(i = info_status; i < 11;i++){
				var opciones = "<option value='"+i+"'>"+estatus[i]+"</options>"; 
			 
				$("#status").append(opciones);
				}
				
				$('select[name="status"]').val(info.status); 


				// $('input[name="evaluation"][value ='+ info.evaluation +']').prop('checked', true); 
				var date = info['deliver_date'].split(/[- :]/);

				$('#status option[value=7]').text("La fecha de tu pedido es el " + date[2] + '-' + date[1] + '-' + date[0]);          
				var product_info = data.products;
				$("#table_products").empty();
				for(var i = 0; i < product_info.length; i++){
					
					var name = "<tr><td>"+product_info[i].name+"</td>" + "<td>"+product_info[i].quantity+"</td>" + "<td>"+product_info[i].unit_price+"</td></tr>";
					$("#table_products").append(name);        
				}
			}
		});
	});

	$('.stars').raty({
			
			score: function() {
				return $(this).attr('data-score');
			},
			scoreName : 'rating',
				path : '/img/raty',
				readOnly: true
	});

	$('#submit-btn').click(function(){
		$('#update-form').submit(); 
	});

	$('.assign-btn').click(function(){
		var action = laroute.action('AdminGeneralRequestsController@update',{general_requests:$(this).attr('data-request-id')});
		$('#assign-modal form').attr('action',action);
		$('#assign-modal').modal();
	});

	$('#submit-btn').click(function(){
		$('#assign-modal form').submit();
  	});

	$('.rating-raty').raty({
		scoreName : 'rating',
		path : '/img/raty'
  	});

	$('.btn-enable').click(function(){
		var action = laroute.action('AdminGeneralRequestsController@update',{general_requests:$(this).attr('data-request-id')});
		$('#enable-modal form').attr('action',action);
		$('#enable-modal').modal();
	});

	$('.btn-disable').click(function(){

		var action = laroute.action('AdminGeneralRequestsController@destroy',{general_requests:$(this).attr('data-request-id')});

		$('#disable-modal form').attr('action',action);
		$('#disable-modal').modal();
	});


});

</script>
@stop
