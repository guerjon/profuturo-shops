@extends('layouts.master')

@section('content')
	
	<div class="row"  >

		<div class="col col-xs-3" style="margin:4%">
			<div class="form-group">
				{{Form::label('since','DESDE')}}
      			{{Form::text('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'), ['class' => 'filter form-control datepicker','id' => 'since' ])}}	
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
				{{Form::text('consultor',null,['class' => 'filter form-control','placeholder datepicker' => '# CONSULTOR','id' => 'consultor'])}}		
      		</div>
			
			<center style="margin-top:10%">
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
		<div class="col col-xs-6 " >
			<div class="chart-container" id ="chart-container" style="float:right;"></div>		
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
											{axis : "¿Cómo consideras la actitud de servicio del consultor?",value: data.surveys[0].uno },
											{axis : "¿Qué te parecio el seguimiento del Consultor?",value: data.surveys[0].dos },
											{axis : "¿Cómo calificarías los tiempos de respuesta?",value: data.surveys[0].tres },
											{axis : "Los productos entregados cumplen con las características solicitadas?",value: data.surveys[0].cuatro },
											{axis : "¿Volverías a usar la plataforma para realizar nuevas solicitudes?",value: data.surveys[0].cinco },
										]
									}
								];
						  
								RadarChart.draw(".chart-container", datos);

								var promedio_total = parseFloat(data.surveys[0].uno) + parseFloat(data.surveys[0].dos) + parseFloat(data.surveys[0].tres)
														+ parseFloat(data.surveys[0].cuatro + parseFloat(data.surveys[0].cinco));
								promedio_total /= 5 ; 

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
	                var solicitud = data.request;
	                
	                $('#consultor').autocomplete(
	                  {
	                    source:ccostos,
	                    minLength: 1,
	                    select: function(event,ui){
	                      actualiza();
	                    },
	                  }
	                );

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

