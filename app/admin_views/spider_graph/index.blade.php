@extends('layouts.master')

@section('content')
	<h1>Estadísticas de Encuestas</h1>
	<hr>

	
		<center>
			<img src="/images/loading.gif" alt="Cargando..." id="loading" width="400" height="400">
		</center>

		<div class="row">	
			<div class="col col-md-4">
				<div class="form-group">
					{{Form::label('gerencia','GERENCIA',['class' => 'label-control'])}}
					{{Form::select(
						'gerencia',
						[null => 'Todos los usuarios'] + User::where('role','user_requests')->lists('gerencia','id'),
						null,
						['class' => 'form-control select-2 filter','id' =>'gerencia'])
					}}
				</div>
			</div>
			<div class="col col-md-4">
				<div class="form-group">
		    		{{Form::label('solicitud','# SOLICITUD',['class' => 'label-control'])}}
					{{Form::select(
						'solicitud',
						[null => 'Todas las solicitudes'] + $surveys,
						null,
						['class' => ' form-control select-2 filter','id' => 'solicitud'])
					}}		
	      		</div>
			</div>
			<div class="col col-md-4">
				<div class="form-group">
					{{Form::label('encuesta','# ENCUESTA',['class' => 'label-control'])}}
					{{Form::select('encuesta',[null => 'Todas las encuestas'] + SatisfactionSurvey::orderBy('id')->lists('id','id'),null,['class' => ' form-control select-2 filter','id' => 'encuesta'])}}
				</div>
			</div>
		</div>
		<div class="row">
			
			<div class="col col-md-4">			
				<div class="form-group">
					<div class="form-group">
						{{Form::label('regional','Encuestas por regional',['class' => 'label-control'])}}
						{{Form::select('regional',[null => 'Todas las regiones'] + Region::lists('name','id'),null,['class' => 'form-control select-2 filter','id' =>'regional'])}}
					</div>
				</div>
			</div>

			<div class="col col-md-4">
				<div class="form-group">
					{{Form::label('since','DESDE')}}
	      			{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'filter form-control datepicker ','id' => 'since'])}}	
				</div>
			</div>
			<div class="col col-md-4">
				<div class="form-group">
		      		{{Form::label('until','HASTA')}}
	    	  		{{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'filter form-control datepicker','id' => 'until' ])}}
				</div>
			</div>
		</div>
		

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
			                <ul class="features">
								<li>Actitud del consultor: <span class="fontawesome-star" id="actitud_consultor"></span ></li>
			                    <li>Seguimiento del consultor: <span class="fontawesome-star" id="seguimiento_consultor"></span></li>
			                    <li>Tiempos respuesta consultor: <span class="fontawesome-star" id="tiempos_respuesta"></span></li>
			                    <li>Calidad del producto: <span class="fontawesome-star" id="calidad_producto"></span></li>
	
			                </ul> 
			            </div>
			        </div>
    			</div>					
			</div>

			<div class="col col-xs-6 text-center">
				<canvas id="chart-container"></canvas>		
			</div>

			<div class="col col-xs-3" >
				<div class="wrapper">
			        <div class="pricing-table group"> 
			            <div class="block professional fl">
			                <h2 class="title">Solicitudes seleccionadas</h2>
			                <div class="content">
			                    <p class="price">
			                        <span id="total_solicitudes"></span>
			                    </p>
			                </div>
			                <ul class="features">

			                    <li><span class="fontawesome-star" id="actitud_consultor"></span >Actitud del consultor: 10</li>
			                    <li><span class="fontawesome-star" id="seguimiento_consultor"></span>Seguimiento del consultor</li>
			                    <li><span class="fontawesome-star" id="tiempos_respuesta"></span>Tiempos respuesta consultor</li>
			                    <li><span class="fontawesome-star" id="calidad_producto"></span>Calidad del producto</li>
	
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
	  
	<script>
		$(function(){

			$('#loading').prop('hidden',true);
			
			$('#formularios').prop('hidden',false);

			$('.select-2').select2({
				theme: "bootstrap",
			});
			
			actualiza();

			$('.filter').change(function(){
				actualiza();
			});

			function actualiza(){
				$.get('/admin/api/survey',{
					since: $('#since').val(),
					until: $('#until').val(),
					consultor: $('#consultor').val(),
					solicitud: $('#solicitud').val(),
					gerencia: $('#gerencia').val(),
					encuesta: $('#encuesta').val(),
					regional: $('#regional').val(),

					},function(data){

						if(data.status = 200){
							
							console.log(data);
								$('#total_solicitudes').html(data.surveys[0].total);
								
								var promedio_total = parseFloat(data.surveys[0].uno) + parseFloat(data.surveys[0].dos) + parseFloat(data.surveys[0].tres)
														+ parseFloat(data.surveys[0].cuatro);
								promedio_total /= 5 ;
								
								$('#promedio_total').html(promedio_total);
								$('#actitud_consultor').html(data.surveys[0].uno);
								$('#seguimiento_consultor').html(data.surveys[0].dos);
								$('#tiempos_respuesta').html(data.surveys[0].tres);
								$('#calidad_producto').html(data.surveys[0].cuatro);
							
								
							if(data.surveys[0].uno != null){

								var data = 
								{
								    labels: [
								    			"Actitud del consultor",
								    			"Seguimiento del consultor",
								    			"Tiempos respuesta consultor",
								    			"Calidad de producto"
								    		],
								    datasets: [{

									            label: "Datos de encuesta",
									            backgroundColor: "rgba(0,74,141,0.2)",
									            borderColor: "rgba(179,181,198,1)",
									            pointBackgroundColor: "rgba(179,181,198,1)",
									            pointBorderColor: "#fff",
									            pointHoverBackgroundColor: "#fff",
									            pointHoverBorderColor: "rgba(179,181,198,1)",
									            data: [
									            		data.surveys[0].uno,
									            		data.surveys[0].dos,
									        			data.surveys[0].tres,
									        			data.surveys[0].cuatro,
									        		]
									        }]			    
								};

								var myRadarChart = new Chart($('#chart-container'), {
								    type: 'radar',
								    data: data,
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

