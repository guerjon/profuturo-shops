@extends('layouts.master')

@section('content')
	<h1>Estadísticas de Encuestas</h1>
	<hr>

	<div class="row">
		<center>
			<img src="/images/loading.gif" alt="Cargando..." id="loading" width="400" height="400">
		</center>
		<div class="col col-xs-5" style="margin:5%" >
			<div class="col col-xs-10" id="formularios" hidden>
				<div class="form-group">
					{{Form::label('since','DESDE')}}
	      			{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'filter form-control datepicker','id' => 'since'])}}	
				</div>
			
				<div class="form-group">
		      		{{Form::label('until','HASTA')}}
	    	  		{{Form::text('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'), ['class' => 'filter form-control datepicker','id' => 'until' ])}}
				</div>
	      		
	      		<div class="form-group">
		    		{{Form::label('solicitud','SOLICITUD')}}
					{{Form::text('solicitud',null,['class' => 'filter form-control','placeholder datepicker' => '# SOLICITUD','id' => 'solicitud'])}}		
	      		</div>
	    	

	      		<div class="form-group">
		    		{{Form::label('consultor','CONSULTOR')}}
					{{Form::select('consultor',[null => "Todos los consultores"] + $consultores,null,['class' => 'filter form-control','placeholder datepicker' => '# CONSULTOR','id' => 'consultor'])}}		
	      		</div>	
		
			
				<center >
					<table class="table table-straped">
						<thead id="thead">
							<th>
								TOTAL SOLICITUDES
							</th>
							<th>
								PROMEDIO TOTAL
							</th>
						</thead>
						<tbody id="tbody">
							
						</tbody>
					</table>
				</center>
			</div>

		</div>
		<div class="col col-xs-5" >
			<div class="chart-container" id ="chart-container" ></div>		
		</div>

	</div>
	

@endsection


@section('script')
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
	<link rel="stylesheet" href="https://rawgit.com/tpreusse/radar-chart-d3/master/src/radar-chart.css">
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<style href="/css/radar-chart.min.css"></style>
	<style href="/css/radar-chart.css"></style>
	<script src="/js/radar-chart.js"></script>
	<script src="/js/radar-chart.min.js"></script>
	  
	<script>

		$(function(){

			$('#loading').prop('hidden',true);
			$('#formularios').prop('hidden',false);

			$('.filter').change(function(){

				actualiza();
			});

			function actualiza(){

				$.get('/admin/api/survey',{
					since: $('#since').val(),
					until: $('#until').val(),
					consultor: $('#consultor').val(),
					solicitud: $('#solicitud').val(),

					},function(data){

						if(data.status = 200){
							$('#chart-container').empty();

							if(data.surveys[0].uno != null){
								var datos = [
									{
										className: data.surveys[0].id,
										axes: [
											{axis : "Actitud del consultor",value: data.surveys[0].uno },
											{axis : "Seguimiento del consultor",value: data.surveys[0].dos },
											{axis : "Tiempos respuesta consultor",value: data.surveys[0].tres },
											{axis : "Calidad de producto",value: data.surveys[0].cuatro },
											{axis : "Facilidad plataforma",value: data.surveys[0].cinco },
										]
									}
								];
						  
								RadarChart.draw(".chart-container", datos);

								var promedio_total = parseFloat(data.surveys[0].uno) + parseFloat(data.surveys[0].dos) + parseFloat(data.surveys[0].tres)
														+ parseFloat(data.surveys[0].cuatro + parseFloat(data.surveys[0].cinco));
								promedio_total /= 5 ; 

								$('#tbody').empty();
								$('#tbody').append('<td>'+data.surveys[0].total+'</td>');
								$('#tbody').append('<td>'+promedio_total+'</td>');



							}else{
								
								$('#chart-container').append('<div class="alert alert-info">No hay resultados suficientes para formar la gráfica.</div>');
							}
						}

				});		
			}

			$.ajax({
	            url : '/admin/api/ccostos-autocomplete',
	            dataType: 'json',
	            success : function(data){
	            
	              if(data.status == 200){


	                var ccostos = data.ccostos;

	                $('#solicitud').autocomplete(
	                  {
	                    source:solicitud,
	                    minLength: 1,
	                    select: function(event,ui){
	                      actualiza();
	                    },
	                  }
	                );

	                
	              }
	            },error : function(data){
	              }
	        });
		});
	
	</script>

@endsection

