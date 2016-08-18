@extends('layouts.master')

  @section('content')
	
	<div class="row">
		<ol class="breadcrumb">
			<a href="{{URL::previous()}}" class="back-btn">
				<span class="glyphicon glyphicon-arrow-left"></span> Regresar
			</a>
			&nbsp;&nbsp;&nbsp;
		  	<li><a href="/">Inicio</a></li>
		  	<li><a href="/admin/reports/index">Reportes</a></li>
		  	<li class="active">Solicitudes generales</li>
		</ol>    
	</div>


	<div class="row">
		{{Form::open(
			[
			  'id' => 'filter-form',
			  'method' => 'GET',
			  'action' => 'AdminApiController@getGeneralRequest',
			  'target' => '_blank'
		  	])
		}}
			<div class="col-xs-4"><h1>Reporte solicitudes generales</h1></div>
			<div class="col-xs-6"></div>
			<div class="col-xs-2">
				{{Form::hidden('page',null,['id' => 'number_page'])}}
			  	<button class="btn btn-primary btn-submit" type="button" id="download-btn">
					<span class="glyphicon glyphicon-download-alt"></span> Descargar excel
			  	</button>
			</div>

		{{Form::close()}}

	</div>
	<hr>
	<div class="row">
		<div class="container-fluid">  	
			<table class="table table-striped">
		  		<thead>
		  		</thead>
		  		<tbody>
		  		</tbody>
			</table>
		</div>
		<center>
		  <ul class="pagination" id="pagination"></ul>
		</center>		
	</div>

  @stop

@section('script')
<script src="/js/manual_pagination.js"></script>
<script>

  function getStatus(pos){
	switch(pos){
	  case 0: return  'Acabo de recibir tu solicitud, en breve me comunicare contigo';
	  break;
	  case 1: return  'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas';
	  break;
	  case 2: return  'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados';
	  break;
	  case 3: return  'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio';
	  break;
	  case 4: return  'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad';
	  break;
	  case 5: return  'Recotizar';
	  break;
	  case 6: return  'Conforme a tu elección, ingresa tu solicitud en People Soft';
	  break;
	  case 7: return  'Ya se envió la orden de compra al proveedor';
	  break;
	  case 8: return  'La fecha de entrega de tu pedido es ';
	  break;
	  case 9: return  'Tu pedido llego en excelentes condiciones, en el domicilio y recibió';
	  break;
	  case 10: return  'Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción.';
	  break;
	  case 11: return 'La encuesta ha sido contestada';
	  break;
	  case 12: return 'Encuesta cancelada;';
	  default: return 'Desconocido';
	}
  }

  function update(){
	$('.table tbody').empty();
	$('.table tbody').append(
	  $('<tr>').attr('class', 'info').append(
		$('<td>').attr('colspan', $('.table thead tr:first-child th').length).html('<strong>Cargando...</strong>')
	  )
	);
	
	var action = laroute.action('AdminApiController@getGeneralRequest');
	console.log(action);
	$.get(action, $('#filter-form').serialize(), function(data){
		
	  $('.table tbody').empty();
	  if(data.status == 200){
		var orders_full = jQuery.parseJSON( data.orders_full );
		var orders = orders_full.data;
		var headers = ['# de sol.','Tipo de proyecto','Usuarios finales','Fecha del evento','Fecha de solicitud','Fecha de entrega','Nombre del proyecto','Lista de distribución','Expectativas','#Productos o servicios','Total Presupuesto','Estatus'];
		var pagination = ('#pagination');

		$('#number_page').val(orders_full.current_page);
		$('.table thead').empty();
		
		if(orders.length == 0){
		  $('.table tbody').append(
			$('<tr>').attr('class', 'warning').append(
			  $('<td>').html('<strong>No hay registros que mostrar</strong>')
			)
		  );
		  $('.btn-submit').prop('disabled', true);
		  $('#pagination').empty();
		  return;
		}else{
		  $('.btn-submit').prop('disabled', false);
		}

		for(var i=0; i<headers.length; i++){
		  $('.table thead').append($('<th>').html(headers[i]));
		}

		headers = ['id','kind','project_dest','project_date','created_at','deliver_date','project_title','distribution_list','comments','total_products','total',
		'status'];

		for(var i=0; i<orders.length; i++){
		  var tr = $('<tr>');

		  for(var j=0; j<headers.length; j++){
			
			if(j==1){  //kind
			 
			  if(orders[i][headers[j]] == 0)
				tr.append($('<td>').html("Producto"));  
			  else
				tr.append($('<td>').html("Servicio"));
			}
			else if (j == 7) // distribution list
			  
			  if(orders[i][headers[j]] == 0)
				tr.append($('<td>').html('No'));  
			  else if (orders[i][headers[j]] == 1)
				tr.append($('<td>').html('Si'));  
			  else
				tr.append($('<td>').html("Pendiente"));              
			
			else if( j == 11)  //status
			  
			  tr.append($('<td>').html(getStatus(orders[i][headers[j]])));              

			else  
			  tr.append($('<td>').html(orders[i][headers[j]]));
		  }

		  $('.table tbody').append(tr);
		}

		$('#pagination').empty();
		firstSpanCreate($('#pagination'),orders_full);
		if(orders_full.total > 100){
		  if(orders_full.current_page > 8 && orders_full.current_page < orders_full.last_page - 2){
			  if(orders_full.current_page+1 == orders_full.last_page - 3){
				spanPointsCreate($('#pagination'));
				listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.last_page+1);            
			  }else{
				listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.current_page+1);            
				spanPointsCreate($('#pagination'));
				listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);      
			  }
		  }else{
			listsCreate($('#pagination'),orders_full,1,9);
			spanPointsCreate($('#pagination'));
			listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);  
		  }
		}else{
			listsCreate($('#pagination'),orders_full,1,orders_full.last_page+1);      
		}
		 lastSpanCreate($('#pagination'),orders_full);
	  }else{
		$('.table tbody').append(
		  $('<tr>').attr('class', 'danger').append(
			$('<td>').attr('colspan', $('.table > thead > tr th').length).html(data.status + ':' + data.error_msg)
		  )
		);
	  }
	});
  }
  $(function(){
	update();
	  $('#filter-form select').change(function(){
		update();
	  });
		$(document).on('click', '.pagina', function(){
		  event.preventDefault();
		  var page = $(this).attr('data-page');
		  $('#number_page').val(page);
		  $('#pagination').empty();
		  update();
		});

	  $('#download-btn').click(function(){
		$('#filter-form').append('<input value="1" type="hidden" name="excel">')
		$('#filter-form').submit();
	  });


  });
</script>
@stop
