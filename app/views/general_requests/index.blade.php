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
					<p>Se necesita contestar las encuesta de satisfacción para continuar.</p>
				  @endif
				@endif
			</div>
		</div>
		<div class="row">
			@if(isset($error))
			  	<div class="alert alert-danger">
					{{$error}}
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
			  <li role="presentation" class="{{$active_tab == 'all' ?  'active' : ''}}">
			    <a href="{{action('GeneralRequestsController@index',['active_tab' =>'all'])}}">Todas</a> 
			  </li>
			  <li role="presentation" class="{{$active_tab == 'assigned' ?  'active' : ''}}">
			    <a href="{{action('GeneralRequestsController@index',['active_tab' =>'assigned'])}}">Asignadas</a> 
			  </li>
			  <li role="presentation" class="{{$active_tab == 'not_assigned' ? 'active' : ''}}">
			    <a href="{{action('GeneralRequestsController@index',['active_tab' =>'not_assigned'])}}">No asignadas</a> 
			  </li> 
			  <li role="presentation" class="{{$active_tab == 'deleted_assigned' ? 'active' : ''}}">
			    <a href="{{action('GeneralRequestsController@index',['active_tab' =>'deleted_assigned'])}}">Canceladas asignadas</a>
			  </li>
			  <li role="presentation" class="{{$active_tab == 'deleted_unassigned' ? 'active' : ''}}">
			    <a href="{{action('GeneralRequestsController@index',['active_tab' =>'deleted_unassigned'])}}">Canceladas no asignadas</a>
			  </li>
			</ul>
		</div>
		<div class="row">
			@if($requests->count() == 0)
				<div class="alert alert-info">
				  Usted no ha hecho ninguna solicitud
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
								<div class="row">
									<div class="col col-xs-6">
										
										<button data-toggle="modal" data-target="#delete-modal" class="btn btn-sm btn-danger btn-delete" data-request-id="{{$request->id}}">
											<i class="fa fa-trash"></i>
											Eliminar
										</button>    	
										
									</div>
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

	$('#guardar').click(function(){
	  $('#update-form').submit();
	});

  });



</script>
@stop
