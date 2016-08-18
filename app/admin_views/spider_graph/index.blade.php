@extends('layouts.master')

@section('content')
	<div class="row">
		<div class="col col-xs-6"><h1>Estadísticas de Encuestas</h1></div>
		<div class="col col-xs-6"></div>
		
	</div>
	<hr>

	
		<center>
			<img src="/images/loading.gif" alt="Cargando..." id="loading" width="400" height="400">
		</center>
		{{Form::open(['method' => 'get'])}}
			<div class="row">	
				<div class="col col-md-2">
					<div class="form-group">
						{{Form::label('gerencia','Consultor',['class' => 'label-control'])}}
						{{Form::select(
							'gerencia',
							[null => 'Todos los consultores'] + User::where('role','manager')->lists('gerencia','id'),
							null,
							['class' => 'form-control select-2 filter','id' =>'gerencia'])
						}}
					</div>
				</div>

				<div class="col col-md-2">
					<div class="form-group">
						{{Form::label('encuesta','NUMERO ENCUESTA',['class' => 'label-control'])}}
						{{Form::select('encuesta',[],null,['class' => ' form-control select-2 filter','id' => 'encuesta','DISABLED'])}}
					</div>
				</div>
			    <div class="col-xs-2 ">
			    	{{Form::label('since','DESDE',['class' => 'label-control'])}}
    				{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'since' ])}}
				</div>
			    <div class="col-xs-2 ">
			    	{{Form::label('until','HASTA',['class' => 'label-control'])}}
			        {{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'form-control datepicker','id' => 'until' ])}}
			    </div>
				<div class="col col-xs-3 text-right" >
					<br>
					
					<input type="text" class="hidden" value="xls" name="xls">
						<button class="btn btn-primary" type="submit">
							<span class="glyphicon glyphicon-download-alt"></span>
							Descargar reporte
						</button>
						<a href="grafica-arana" class="btn btn-primary">
							<i class="fa fa-eraser" aria-hidden="true"></i>
							Borrar Filtros
						</a>
				</div>
			</div>
		{{Form::close()}}				
		
		<hr>
		<div class="row">
			<div class="col col-xs-6" id="div-chart-container">
				<canvas id="chart-container"></canvas>		
			</div>			
			<div class="col-xs-1"></div>
			<div class="col-xs-5">
				<div class="panel panel-default">
  					<div class="panel-heading"><h3>Promedio total :<span id="promedio_total"></span></h3></div>
  					<div class="panel-body">
                        <ul style="color:black;font-size:15px">
							<li class="well"> Actitud del consultor: <b><span id="actitud_consultor"></span ></b></li>
		                    <li class="well">Seguimiento del consultor: <b><span id="seguimiento_consultor"></b></span></li>
		                    <li class="well">Tiempos respuesta consultor: <b><span id="tiempos_respuesta"></b></span></li>
		                    <li class="well">Calidad del producto: <b><span id="calidad_producto"></span></b></li>
		                </ul> 	                      						
  					</div>
				</div>
			</div>		
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3> Número de solicitudes: <span id="total_solicitudes"></span> </h3>
					</div>
					<div class="panel-body">
						<table class="table table-striped">
							<thead>
								<th>Número de solicitud</th>
								<th>Implementación de estrategias y proactividad para identificar soluciones</th>
								<th>Adecuación y control al proceso de Papelería</th>
								<th>Cumplimiento del proceso de papelería 3</th>
								<th>Evaluación mensual de proveedores</th>
								<th>Encuesta de calidad de usuarios /actitud de servicio</th>								
							</thead>
							<tbody id="comments">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
@endsection


@section('script')
@parent
	<style href="/css/table_style.css"></style>
	<script type="text/javascript" src="/js/chart/dist/Chart.js"></script>
	<script type="text/javascript" src="/js/jquery.number.min.js"></script>
	<script>
		$(function(){

			$('#loading').prop('hidden',true);
			
			$('#formularios').prop('hidden',false);

			$('.select-2').select2({
				theme: "bootstrap",
			});
			
			actualiza();

			$('#gerencia').change(function(){
				$('#encuesta').empty().attr('disabled',true);
				actualiza();
			});	

			$('#encuesta').change(function(){
				actualiza();
			});

			$('.datepicker').change(function(argument) {
				actualiza();
			});

			function actualiza(){
				
				$.get('/admin/api/survey',{
					gerencia: $('#gerencia').val(),
					encuesta: $('#encuesta').val(),
					since: $('#since').val(),
					until: $('#until').val()
					},function(datos){
						
						var encuestas = [];
						
						if(datos.status = 200){
							
							if(datos.encuestas.length > 0 ){
								$('#encuesta').attr('disabled',false);

								$('#encuesta').empty();
								for (var i = datos.encuestas.length - 1; i >= 0; i--) {
									
									$('#encuesta').append('<option value="'+datos.encuestas[i]+'">'+
										"Encuesta "+datos.encuestas[i] + ", "+ "Solicitud general " + " "+ datos.solicitudes[i]+'</option>')
								};								
							}

							$('#total_solicitudes').html(datos.surveys[0].total);
							
							var promedio_total = parseFloat(datos.surveys[0].uno) + parseFloat(datos.surveys[0].dos) + parseFloat(datos.surveys[0].tres) + parseFloat(datos.surveys[0].cuatro);

							promedio_total /= 4 ;

							
							$('#promedio_total').html($.number(promedio_total,'3'));
							$('#actitud_consultor').html($.number(datos.surveys[0].uno,'3'));
							$('#seguimiento_consultor').html($.number(datos.surveys[0].dos,'3'));
							$('#tiempos_respuesta').html($.number(datos.surveys[0].tres,'3'));
							$('#calidad_producto').html($.number(datos.surveys[0].cuatro,'3'));
							
							$('#comments').empty();
							for (var i = datos.comments.length - 1; i >= 0; i--) {
								
									$('#comments').append(
											"<tr><td><a href='general-requests/"+datos.comments[i].general_request_id+"'>"+ datos.comments[i].general_request_id+
											"</a></td>"+
											'<td>'+datos.comments[i].explain_1+'</td>'+
									 	 	'<td>'+datos.comments[i].explain_2+'</td>'+
											'<td>'+datos.comments[i].explain_3+'</td>'+
											'<td>'+datos.comments[i].explain_4+'</td></tr>');
									
							};
							
							if(datos.surveys[0].uno != null){

								var data = 
								{
								    labels: [	
								    			"Actitud del consultor",
								    			"Seguimiento del consultor",
								    			"Tiempos respuesta consultor",
								    			"Calidad de producto",
								    		],
								    datasets: [{

									            label: "Datos de encuesta",
									            backgroundColor: "rgba(0,74,141,0.2)",
									            borderColor: "rgba(179,181,198,1)",
									            pointBackgroundColor: "rgba(179,181,198,1)",
									            pointBorderColor: "#fff",
									            pointHoverBackgroundColor: "#fff",
									            pointHoverBorderColor: "rgba(179,181,198,1)",
									            data: 
									            	[
									            		parseFloat(datos.surveys[0].uno),
									            		parseFloat(datos.surveys[0].dos),
									        			parseFloat(datos.surveys[0].tres),
									        			parseFloat(datos.surveys[0].cuatro),
									        		]
									        }],
									fill : true			    
								};
								$('#div-chart-container').empty();
								$('#div-chart-container').append('<canvas id="chart-container"></canvas>')	
								var myRadarChart = new Chart($('#chart-container'), {
								    type: 'bar',
								    data: data,
								    options: {
								        scales: {
								            yAxes: [{
								                ticks: {
								                    max: 10,
								                    min: 0,
								                    stepSize: 2
								                }
								            }]
								        }
							    	}
								});
								
							}else{
								alert("No hay datos suficientes para formar la gráfica.");
							}
						}
				});		
			}

		});

		
	
	</script>

@endsection

