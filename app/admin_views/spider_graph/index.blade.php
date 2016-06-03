@extends('layouts.master')

@section('content')
	<div class="row">
		<div class="col col-xs-3"><h1>Estadísticas de Encuestas</h1></div>
		<div class="col col-xs-6"></div>
		
	</div>
	

	<hr>

	
		<center>
			<img src="/images/loading.gif" alt="Cargando..." id="loading" width="400" height="400">
		</center>

		<div class="row">	
			<div class="col col-md-3">
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

			<div class="col col-md-3">
				<div class="form-group">
					{{Form::label('encuesta','NUMERO ENCUESTA',['class' => 'label-control'])}}
					{{Form::select('encuesta',[],null,['class' => ' form-control select-2 filter','id' => 'encuesta','DISABLED'])}}
				</div>
			</div>
			<div class="col col-xs-3 text-right" >
				<br>
				{{Form::open(['method' => 'get'])}}
				<input type="text" class="hidden" value="xls" name="xls">
					<button class="btn btn-primary" type="submit">
						<span class="glyphicon glyphicon-download-alt"></span>
						Descargar reporte
					</button>
				{{Form::close()}}				
			</div>

		</div>

		
		<hr>
		<div class="row">
			<div class="col col-xs-3">
				<div class="wrapper">
			        <div class="pricing-table group"> 
			            <div class="block professional fl">
			                <h2 class="title">Promedio</h2>
			                <div class="content">
			                    <p class="price">
			                        <span id="promedio_total"></span>
			                    </p>
			                </div>
			                <ul class="features" style=" overflow: scroll;height: 300px;color:black;font-size:15px">
								<li>Actitud del consultor: <span class="fontawesome-star" id="actitud_consultor"></span ></li>
			                    <li>Seguimiento del consultor: <span class="fontawesome-star" id="seguimiento_consultor"></span></li>
			                    <li>Tiempos respuesta consultor: <span class="fontawesome-star" id="tiempos_respuesta"></span></li>
			                    <li>Calidad del producto: <span class="fontawesome-star" id="calidad_producto"></span></li>
	
			                </ul> 
			            </div>
			        </div>
    			</div>					
			</div>

			<div class="col col-xs-6 text-center" id="div-chart-container">
				<canvas id="chart-container"></canvas>		
			</div>

			<div class="col col-xs-3" >
				<div class="wrapper">
			        <div class="pricing-table group"> 
			            <div class="block professional fl">
			                <h2 class="title">Solicitudes promediadas</h2>
			                <div class="content">
			                    <p class="price">
			                        <span id="total_solicitudes"></span>
			                    </p>
			                </div>
			                <div id="general-requests-id"></div>
			                <ul class="features" id="comments" style=" overflow: scroll;height: 300px;color:black;font-size:15px">

			                    
	
			                </ul>
			            </div>
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

			function actualiza(){
				
				$.get('/admin/api/survey',{
					gerencia: $('#gerencia').val(),
					encuesta: $('#encuesta').val(),
					},function(datos){
						console.log(datos);
						if(datos.status = 200){
							
							if(datos.encuestas.length > 0 ){
								$('#encuesta').attr('disabled',false);
								$('#encuesta').select2({theme: "bootstrap",data: datos.encuestas});
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
										"<h3><a href='general-requests/"+datos.comments[i].general_request_id+"'>Solitud general:"+ datos.comments[i].general_request_id+"</a></h3>");
									$('#comments').append('<ul>');
									$('#comments').append('<li>'+datos.comments[i].explain_1+'</li>')
									$('#comments').append('<li>'+datos.comments[i].explain_2+'</li>')
									$('#comments').append('<li>'+datos.comments[i].explain_3+'</li>')
									$('#comments').append('<li>'+datos.comments[i].explain_4+'</li>')
									$('#comments').append('</ul>');
								
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

