@extends('layouts.master')

@section('content')
	
	{{Form::open([
			'action' => 'SatisfactionSurveyController@postSatisfactionSurvey',
			'role' => 'form',
			'class' => 'form-inline',	
	])}}

<table class="table table-striped">

	<thead>
		<th class="alert alert-info">
		Por favor responde las siguiente preguntas.  Donde 1 es malo y 5 es regular.
		</th>
		<th class="alert alert-info">
			
		</th>
		<th class="alert alert-info">
			
		</th>
		<th class="alert alert-info">
			
		</th>
		<th class="alert alert-info">
			
		</th>
		<th class="alert alert-info">
			
		</th>
		<th class="alert alert-info" >
			
		</th>
	</thead>
	<tbody>
		<tr>
			<td> 
				{{Form::label('question_one','Implementación de estrategias y proactividad para identificar soluciones',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_one','1')}}
						{{Form::radio('question_one','1')}}	
			</td>
			<td>
						{{Form::label('question_one','2')}}
						{{Form::radio('question_one','2')}}	
			</td>
			<td>
						{{Form::label('question_one','3')}}
						{{Form::radio('question_one','3')}}		
			</td>
			<td>
						{{Form::label('question_one','4')}}
						{{Form::radio('question_one','4')}}	
			</td>
			<td>
						{{Form::label('question_one','5')}}
						{{Form::radio('question_one','5')}}	
			</td>
			<td>
						{{Form::label('question_one','6')}}
						{{Form::radio('question_one','6')}}		
			</td>			
		</tr>
		<tr>
			<td>
				{{Form::label('question_two','Adecuación y control al proceso de Papelería',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_two','1')}}
						{{Form::radio('question_two','1')}}	
			</td>
			<td>
						{{Form::label('question_two','2')}}
						{{Form::radio('question_two','2')}}	
			</td>
			<td>
						{{Form::label('question_two','3')}}
						{{Form::radio('question_two','3')}}		
			</td>
			<td>
						{{Form::label('question_two','4')}}
						{{Form::radio('question_two','4')}}	
			</td>
			<td>
						{{Form::label('question_two','5')}}
						{{Form::radio('question_two','5')}}	
			</td>
			<td>
						{{Form::label('question_two','6')}}
						{{Form::radio('question_two','6')}}		
			</td>						
		</tr>
		<tr>
			<td>
				{{Form::label('question_three','Cumplimiento del proceso de papelería	3',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_three','1')}}
						{{Form::radio('question_three','2')}}	
			</td>
			<td>
						{{Form::label('question_three','2')}}
						{{Form::radio('question_three','2')}}	
			</td>
			<td>
						{{Form::label('question_three','3')}}
						{{Form::radio('question_three','3')}}		
			</td>
			<td>
						{{Form::label('question_three','4')}}
						{{Form::radio('question_three','4')}}	
			</td>
			<td>
						{{Form::label('question_three','5')}}
						{{Form::radio('question_three','5')}}	
			</td>
			<td>
						{{Form::label('question_three','6')}}
						{{Form::radio('question_three','6')}}		
			</td>									
		</tr>
		<tr>
			<td>
				{{Form::label('question_four','Evaluación mensual de proveedores',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_four','1')}}
						{{Form::radio('question_four','1')}}	
			</td>
			<td>
						{{Form::label('question_four','2')}}
						{{Form::radio('question_four','2')}}	
			</td>
			<td>
						{{Form::label('question_four','3')}}
						{{Form::radio('question_four','3')}}		
			</td>
			<td>
						{{Form::label('question_four','4')}}
						{{Form::radio('question_four','4')}}	
			</td>
			<td>
						{{Form::label('question_four','5')}}
						{{Form::radio('question_four','5')}}	
			</td>
			<td>
						{{Form::label('question_four','6')}}
						{{Form::radio('question_four','6')}}		
			</td>									
		</tr>
		<tr>
			<td>
				{{Form::label('question_five','Encuesta de calidad de usuarios /actitud de servicio',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_five','1')}}
						{{Form::radio('question_five','1')}}	
			</td>
			<td>
						{{Form::label('question_five','2')}}
						{{Form::radio('question_five','2')}}	
			</td>
			<td>
						{{Form::label('question_five','3')}}
						{{Form::radio('question_five','3')}}		
			</td>
			<td>
						{{Form::label('question_five','4')}}
						{{Form::radio('question_five','4')}}	
			</td>
			<td>
						{{Form::label('question_five','5')}}
						{{Form::radio('question_five','5')}}	
			</td>
			<td>
						{{Form::label('question_five','6')}}
						{{Form::radio('question_five','6')}}		
			</td>									
		</tr>
		<tr>
			<td>
				{{Form::label('question_six','Proactiviad con proveedores: Búsqueda, Conocimiento, Negociación, Comunicación, Seguimiento ',['class' => 'control-label'])}}
			</td>
			<td>
						{{Form::label('question_six','1')}}
						{{Form::radio('question_six','0')}}	
			</td>
			<td>
						{{Form::label('question_six','2')}}
						{{Form::radio('question_six','1')}}	
			</td>
			<td>
						{{Form::label('question_six','3')}}
						{{Form::radio('question_six','2')}}		
			</td>
			<td>
						{{Form::label('question_six','4')}}
						{{Form::radio('question_six','3')}}	
			</td>
			<td>
						{{Form::label('question_six','5')}}
						{{Form::radio('question_six','4')}}	
			</td>
			<td>
						{{Form::label('question_six','6')}}
						{{Form::radio('question_six','5')}}		
			</td>									
		</tr>
	</tbody>

	
</table>

 		<div class="text-center">
    {{Form::submit('Enviar', ['class' => 'btn btn-warning'])}}
 		</div>	

 
{{Form::close()}}	
@stop


