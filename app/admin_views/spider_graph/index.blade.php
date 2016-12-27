@extends('layouts.master')

@section('content')
	<div class="row">
		<div class="col col-xs-6" title="En esta sección solo aparecen las solicitudes con encuesta contestada."><h1>Estadísticas de Encuestas</h1></div>
		<div class="col col-xs-6"></div>
	</div>
	<hr>
		<center>
			<img src="/images/loading.gif" alt="Cargando..." id="loading" width="400" height="400">
		</center>
			<div class="row">	
				{{Form::open(['method' => 'GET','id' => 'spider-form','target' => '_blank','action' => 'AdminApiController@getSurvey'])}}
				<div class="col col-md-2">
					<div class="form-group">
						{{Form::label('gerencia','CONSULTOR',['class' => 'label-control'])}}
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
						{{Form::label('encuesta','SOLICITUD GENERAL',['class' => 'label-control'])}}
						{{Form::select('encuesta',[],null,['class' => ' form-control select-2 filter','id' => 'encuesta','DISABLED'])}}
					</div>
				</div>
			    <div class="col-xs-2 ">
			    	{{Form::label('since','DESDE',['class' => 'label-control'])}}
    				{{Form::text('since',Input::get('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(6)->format('Y-m-d')) , ['class' => 'form-control datepicker','id' => 'since' ])}}
				</div>
			    <div class="col-xs-2 ">
			    	{{Form::label('until','HASTA',['class' => 'label-control'])}}
			        {{Form::text('until',Input::get('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d')), ['class' => 'form-control datepicker','id' => 'until' ])}}
			    </div>

				{{Form::close()}}	

				<div class="col col-xs-3 text-right" >
					<br>							
					<button class="btn btn-primary" id="filtrar">
						<span class="fa fa-filter"></span>
						Filtrar
					</button>
					<button class="btn btn-default" type="button" id="btn-excel">
						<span class="glyphicon glyphicon-download-alt"></span>
						Descargar Reporte
					</button>
					<a href="grafica-arana" class="btn btn-default">
						<i class="fa fa-eraser" aria-hidden="true"></i>
						Borrar Filtros
					</a>
				</div>
			</div>
		<hr>
		<div class="row">
			<div class="col col-xs-6">
				<div class="panel panel-default">
					<canvas id="chart-container"></canvas>				
				</div>
			</div>			
			<div class="col-xs-6">
				<div class="panel panel-default">
  					<div class="panel-heading"><h3>Promedio total :<span id="promedio_total"></span></h3></div>
  					<div class="panel-body">
                        <ul style="color:black;font-size:15px">
							<li class="well">Actitud del consultor: <b><span id="actitud_consultor"></span ></b></li>
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
						<table class="table table-striped" style="border-collapse:collapse; table-layout:fixed; ">
							<thead>
								<th style="max-width: 50px">Número de solicitud general</th>
								<th style="max-width: 100px">Actitud del consultor:</th>
								<th style="max-width: 100px">Seguimiento del consultor:</th>
								<th style="max-width: 100px">Tiempos respuesta consultor:</th>
								<th style="max-width: 100px">Calidad del producto:</th>
								<th style="max-width: 100px">Fecha de solicitud</th>
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
		function fillSurveySelect(id){
			$.get('/admin/api/general-requests-by-manager',{id:id},function(data){
				
				var datos = data.requests;

				if(datos.length > 0 ){
					$('#encuesta').attr('disabled',false);
					$("#encuesta").html("");
					
					var array = {};
					
					$("#encuesta").html('').select2({data: [{id: '', text: 'Todas las solicitudes'}],theme:'bootstrap'})

					for (var i = datos.length - 1; i >= 0; i--) {
						var newOption = new Option(datos[i].id, datos[i].id, true, false);
						$("#encuesta").append(newOption).trigger('change');
					};
					
				}else{
					$("#encuesta").html("");
					$('#encuesta').attr('disabled',true);
				}
			});
		}

		function actualiza(){

			$.get('/admin/api/survey',$('#spider-form').serialize(),function(datos){
				console.log(datos);
				var encuestas = [];
				
				if(datos.status = 200){
					var solicitudes = datos.solicitudes;
					var numero_solicitudes = solicitudes.length;

					if(numero_solicitudes > 0){
						$('#total_solicitudes').html(numero_solicitudes);
						var promedio = 0;
						var uno_promedio = 0;
						var dos_promedio = 0;
						var tres_promedio = 0;
						var cuatro_promedio = 0;

						for (var i = 0; i < solicitudes.length; i++) {
							promedio = solicitudes[i].promedio + promedio;
							uno_promedio = solicitudes[i].question_one + uno_promedio;
							dos_promedio = solicitudes[i].question_two + dos_promedio;
							tres_promedio = solicitudes[i].question_three + tres_promedio;
							cuatro_promedio = solicitudes[i].question_four + cuatro_promedio;
						};


						promedio = promedio/numero_solicitudes;
						uno_promedio = uno_promedio/numero_solicitudes;
						dos_promedio = dos_promedio/numero_solicitudes;
						tres_promedio = tres_promedio/numero_solicitudes;
						cuatro_promedio = cuatro_promedio/numero_solicitudes;


							$('#promedio_total').html($.number(promedio,'3'));
							$('#actitud_consultor').html($.number(uno_promedio,'3'));
							$('#seguimiento_consultor').html($.number(dos_promedio,'3'));
							$('#tiempos_respuesta').html($.number(tres_promedio,'3'));
							$('#calidad_producto').html($.number(cuatro_promedio,'3'));
							$('#comments').empty();

							for (var i = solicitudes.length - 1; i >= 0; i--) {
								$('#comments').append(
										"<tr><td style='width:50px; word-wrap:break-word;'><a href='general-requests/"+solicitudes[i].general_request_id+"'>"+ solicitudes[i].general_request_id+
										"</a></td>"+
										'<td style="width:100px; word-wrap:break-word;">'+solicitudes[i].explain_1+'</td>'+
								 	 	'<td style=" width:100px; word-wrap:break-word;">'+solicitudes[i].explain_2+'</td>'+
										'<td style="width:100px; word-wrap:break-word;">'+solicitudes[i].explain_3+'</td>'+
										'<td style="width:100px; word-wrap:break-word;">'+solicitudes[i].explain_4+'</td>' +
										'<td style="width:100px; word-wrap:break-word;">'+solicitudes[i].general_request_created_at+'</td>' +
										'</tr>');
									
							};							
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
							            		parseFloat(uno_promedio),
							            		parseFloat(dos_promedio),
							        			parseFloat(tres_promedio),
							        			parseFloat(cuatro_promedio),
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
						alert("No se encontraron solicitudes para estos filtros");
					}						
				}
			});		
		}

		$(function(){
			 
			$('#loading').prop('hidden',true);
			$('#formularios').prop('hidden',false);
			
			$('#gerencia').select2({
				theme: "bootstrap",
			});			

			$('#encuesta').select2({
				theme: "bootstrap",
				placeholder : 'Seleccione una solcitud'
			});

			$('#gerencia').on('select2:select',function(e){
				fillSurveySelect(e.params.data.id);
			});

			actualiza();

			$('#filtrar').click(function(){
				$('#xls').remove();
				actualiza();
			});

			$('#btn-excel').click(function(){
				$('#spider-form').append('<input class="hidden" value="xls" name="xls" id="xls">');
				$('#spider-form').submit();
			});

		});

		
	
	</script>

@endsection

