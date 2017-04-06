@extends('layouts.master')

@section('content')
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-4">
				<h1>Solicitudes generales</h1>
			</div>
			<div class="col-xs-6"></div>
			<div class="col-xs-2">
				@if(Auth::user()->isUserRequests)
				  @if($access)
					<a data-toggle="modal" data-target="#create-request-modal" class="btn btn-warning">
						<span class="fa fa-plus"></span>
						Nueva solicitud
					</a>
				  @else
					<p>Para añadir una nueva encuesta necesita no tener ninguna encuesta sin contestar en la sección de concluidas.</p>
				  @endif
				@endif
			</div>
		</div>
		<div class="row">
			@if($errors->count() > 0)
			  	<div class="alert alert-danger">

			  		Este perfil no cuenta con alguno de los siguientes datos:
			  		<ul>
			  			@if(!Auth::user()->num_empleado)
			  				<li>
			  					Número de empleado
			  				</li>
						@endif

						@if(!Auth::user()->extension)
			  				<li>
			  					Extensión de empleado
			  				</li>
			  			@endif

			  			@if(!Auth::user()->gerencia)
			  				<li>
			  					Gerencia
			  				</li>
			  			@endif

			  			@if(!Auth::user()->email)
			  				<li>
			  					Email
			  				</li>
			  			@endif

			  			@if(!Auth::user()->celular)
			  				<li>
			  					Celular
			  				</li>
			  			@endif
			  		</ul>
			  		Para completar la solicitud se necesitan llenar todos los datos.
			  	</div>
			@endif
			@if(isset($success))
			  	<div class="alert alert-success">
					{{$success}}
			  	</div>
			@endif 
		</div>
		<hr>	
		<div class="row">

			<ul class="nav nav-tabs">
				@if(Auth::user()->role == 'manager')
					<li role="presentation" class="{{$active_tab == 'all' ?  'active' : ''}}">
						<a href="{{action('UserRequestsController@index',['active_tab' =>'all'])}}">Todas</a> 
					</li>
					<li role="presentation" class="{{$active_tab == 'in_process' ?  'active' : ''}}">
						<a href="{{action('UserRequestsController@index',['active_tab' =>'in_process'])}}">En proceso</a> 
					</li>
					<li role="presentation" class="{{$active_tab == 'concluded' ? 'active' : ''}}">
						<a href="{{action('UserRequestsController@index',['active_tab' =>'concluded'])}}">Concluidas</a> 
					</li> 
					<li role="presentation" class="{{$active_tab == 'canceled' ? 'active' : ''}}">
						<a href="{{action('UserRequestsController@index',['active_tab' =>'canceled'])}}">Canceladas</a> 
					</li>
				@else
					<li role="presentation" class="{{$active_tab == 'all' ?  'active' : ''}}">
						<a href="{{action('GeneralRequestsController@index',['active_tab' =>'all'])}}">Todas</a> 
					</li>
					<li role="presentation" class="{{$active_tab == 'in_process' ?  'active' : ''}}">
						<a href="{{action('GeneralRequestsController@index',['active_tab' =>'in_process'])}}">En proceso</a> 
					</li>
					<li role="presentation" class="{{$active_tab == 'concluded' ? 'active' : ''}}">
						<a href="{{action('GeneralRequestsController@index',['active_tab' =>'concluded'])}}">Concluidas</a> 
					</li> 
					<li role="presentation" class="{{$active_tab == 'canceled' ? 'active' : ''}}">
						<a href="{{action('GeneralRequestsController@index',['active_tab' =>'canceled'])}}">Canceladas</a> 
					</li>
				@endif
			</ul>
		</div>
		<div class="row">
			@if($requests->count() == 0)
				<div class="alert alert-info">
				  No se encontraron solicitudes.
				</div>
			@else
				<table class="table table-striped">
					<thead>
						<th>
							ID de Solicitud
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
						  	Fecha de solicitud
						</th>
						<th>
							Línea de negocio
						</th>
						@if(Auth::user()->role == 'manager')
							<th>
								Número orden people
							</th>
						@endif
						<th>
						</th>
					</thead>
					<tbody>
					  	@foreach($requests as $request)
					  		<tr>
					  			
								<td>
									<a href="" class="detail-btn" data-toggle="modal" data-target="#request-modal" data-request-id="{{$request->id}}" >
										{{$request->id}}
									</a>
								</td>
								<td>
								  	{{$request->project_title}}
								</td>
								<td>
								  	{{$request->status_str}}
								</td>
								<td>
								  	{{ $request->total}}
								</td>
								<td>
								  	{{ $request->created_at}}
								</td>
								<td>
									{{$request->linea_negocio}}
								</td>
								@if(Auth::user()->role == 'manager')
									<th>
										{{ $request->num_orden_people}}	
									</th>
								@endif
					  			<td>
								<div class="row">
									<div class="col col-xs-6">
										@if(!$request->trashed() && $request->status < 10)
											<button data-toggle="modal" data-target="#delete-modal" class="btn btn-sm btn-danger btn-delete" data-request-id="{{$request->id}}">
												<i class="fa fa-trash"></i>
												 Eliminar
											</button>    	
										@endif
									</div>

								  	@if(Auth::user()->role == 'user_requests')
										  	<div class="col col-xs-6">
												<?
												$access_survey = false;
												$surveys = DB::table('general_requests')
												  ->join('satisfaction_surveys','satisfaction_surveys.general_request_id','=','general_requests.id')
												  ->where('satisfaction_surveys.general_request_id',$request->id)->count();

													if($request->status == 10 and $surveys == 0)
													$access_survey = true;
													?>
													@if($access_survey)
														{{Form::open(['method' => 'get','action' => 'SatisfactionSurveyController@getSurvey'])}}
															<input type="text" class="hide" value="{{$request->id}}" name="general_request_id">
															<button type="submit" class="btn btn-sm btn-info btn-survey" data-request-id="{{$request->id}}">Contestar encuesta</button>
														{{Form::close()}}
													@endif    
										  	</div>
								  	@endif
								</div>
					  			</td>
					  			</tr>
					  	@endforeach
					</tbody>
				</table>
			@endif	
		</div>		
	</div>


@include('general_requests.partials.satisfaction_survey') 
@include('general_requests.partials.show')
@include('general_requests.partials.survey')
@include('general_requests.partials.confirmation_delete')

@stop

@section('script')
<script src="/js/advancedStepper.js"></script>
<script src="/js/jquery-ui.min.js" ></script>
<script src="/js/hasManyForm.js" ></script>

<script charset="utf-8">
  function calcularPresupuesto(elem){

	var parent = $(elem).parents('.product-form-container');
	var inputQuantity = parent.find('input[name="quantity[]"]').val();
	var inputPrice = parent.find('input[name="unit_price[]"]').val();
	var inputBudget = parent.find('input[name="budget[]"]');

	if((inputQuantity != undefined) && (inputPrice != undefined) && (inputQuantity.length > 0) && (inputPrice.length > 0)){
	  inputQuantity = parseInt(inputQuantity);
	  inputPrice = parseInt(inputPrice);
	  inputBudget.val(inputQuantity * inputPrice);
	  inputBudget.change(); 
	}
  }

  $(function(){
	   
	var currentDate = new Date();

	$.datepicker.regional['es'] = {

		minDate: currentDate,
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$('.datepicker').prop('readonly', true).css('background-color', 'white').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datepicker[min-date]').each(function(){
		$(this).datepicker('option', 'minDate', new Date($(this).attr('min-date') + ' 00:00:00 GMT-0500'));
	});
	

	$('.btn-delete').click(function(){
	  var id = $(this).attr('data-request-id');
	  $('#form-delete').attr('action','/solicitudes-generales/'+id);
	  $('#form-delete').attr('method','DELETE');
	})


	$('#eliminar').click(function(){
	  $('#form-delete').submit();
	});


	$('.detail-btn').click(function(){

	  $.get('/api/request-info/' + $(this).attr('data-request-id'), function(data){
		if(data.status == 200){
		  var info = data.request;
		  for(key in info){
			$('#request-' + key).text(info[key]);         
		  }
		  $('input[name="request_id"]').val(info.id); 

		  var estatus = ['Acabo de recibir tu solicitud, en breve me comunicare contigo',
					   'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas',
					   'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados',
					   'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio',
					   'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad',
					   'Recotizar',
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

		  var date = info['deliver_date'].split(/[- :]/);

		  $('#status option[value=8]').text("La fecha de tu pedido es el " + date[2] + '-' + date[1] + '-' + date[0]);          
		  var product_info = data.products;
		  $("#table_products").empty();
		  for(var i = 0; i < product_info.length; i++){
			
			var name = "<tr><td>"+product_info[i].name+"</td>" + "<td>"+product_info[i].quantity+"</td>" + "<td>"+product_info[i].unit_price+"</td></tr>";
			$("#table_products").append(name);        
		  } 
		   
		   
		   
		  }
		  
	  });
		$('#guardar').attr('data-request-id',$(this).attr('data-request-id'));
	});

	  $('#create-request-modal').advancedStepper();
	  $('.btn-next').prop('disabled', true);
	  $(document).on('keyup keydown change','div[data-step-num] input,div[data-step-num] textarea', function(){
		var llenos = true;
	  $(this).parents('div[data-step-num]').find('input,textarea').each(function(){

		  llenos = llenos && $(this).val() !== undefined && $(this).val().length > 0;
		});
	   $(this).parents('div[data-step-num]').find('.btn-next').prop('disabled', !llenos);
	  });

	 //resultado de presupuesto

	$(document).on('change','input[name="quantity[]"], input[name="unit_price[]"]',function(){
	  calcularPresupuesto(this);
	});

		
	$('#products').hasManyForm({
	 defaultForm: '#product',
	 addButton: '#addButton',
	 dismissButton: '.dismissButton',
	 container: '.product-form-container'
	});

	$('#addButton').click();
	$('#products .product-form-container .dismissButton').remove();

	$('.survey-btn').click(function(){
	  $('#general_request_id').val($(this).attr('data-request-id'));
	});

	$('#guardar').click(function(event){
		event.preventDefault();
		var action = laroute.action('GeneralRequestsController@update',{solicitudes_generales: $(this).attr('data-request-id')});
		console.log(action);
		$('#update-form').attr('action',action);
	  	$('#update-form').submit();
	});

	
	$(document).on('change','#status',function(){
		if( $(this).val() == 10 )
			$('.hidden-cols').removeClass('hide')
		else
			$('.hidden-cols').addClass('hide')
	});

  });
	



</script>
@stop
