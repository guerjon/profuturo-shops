@extends('layouts.master')

@section('content')
	
@if(isset($survey))
	<div class="alert alert-info">La encuesta ya se ha sido contestada. Gracias.</div>
@else
<div class="row" style="margin-top:10%;">
  <div class="col-sm-8 col-sm-offset-2">
		<div class="form-group">
			{{Form::open([
					'action' => ['SatisfactionSurveyController@postQuestions',$general_request->id],
					'role' => 'form',
			])}}

          <table class="table table-striped">

            <thead>
              <th class="alert alert-info">
              Por favor responde las siguiente preguntas.
              </th>
              <th class="alert alert-info" stlye="border-color:#d9edf7;">
                
              </th>
              <th class="alert alert-info" stlye="border-left:#d9edf7;">
                
              </th>
              <th class="alert alert-info" stlye="border-left:#d9edf7;">
                
              </th>
              <th class="alert alert-info" stlye="border-left:#d9edf7;">
                
              </th>

            </thead>
            <tbody>
              <tr>
                <td> 
                  {{Form::label('question_one','¿Cómo consideras la actitud de servicio del consultor?')}}
                </td>
                <td>
                  <label class="radio-inline">
                    {{Form::radio('question_one','1',['default' => 'true'])}} Excelente
                  </label>
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                      {{Form::radio('question_one','2')}}Buena
                  </label>
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','3')}}Mala  
                  </label>                      
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','4')}} Muy mala
                  </label>                      
                </td>     
              </tr>
              <tr>
                <td>
                  {{Form::label('question_two','¿Qué te parecio el seguimiento del Consultor?')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_two','1',['default' => 'true'])}} Excelente
                      </label>
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                        {{Form::radio('question_two','2')}}Bueno
                    </label>
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','3')}}Malo  
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','4')}} Muy malo
                    </label>

                      
                      
                </td>           
              </tr>
              <tr>
                <td>
                  {{Form::label('question_three','¿Cómo calificarías los tiempos de respuesta?')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_three','1',['default' => 'true'])}} Excelentes
                      </label>
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                        {{Form::radio('question_three','2')}}Buenos
                    </label>
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','3')}}Malos 
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','4')}} Muy malos
                    </label>                      
                </td>
        </tr>
              <tr>
                <td>
                  {{Form::label('question_four','¿Los productos entregados cumplen con las características solicitadas?')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_four','1',['default' => 'true'])}} Totalmente de acuerdo
                      </label>
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                        {{Form::radio('question_four','2')}}De acuerdo 
                    </label>
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','3')}}En desacuerdo   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','4')}} Totalmente en desacuerdo
                    </label>
                </td>
    
              </tr>
              <tr>
                <td>
                  {{Form::label('question_five','¿Volverías a usar la plataforma para realizar nuevas solicitudes?')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_five','1',['default' => 'true'])}} Totalmente de acuerdo
                      </label>
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                        {{Form::radio('question_five','2')}}De acuerdo
                    </label>
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','3')}}En desacuerdo  
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','4')}} Totalmente en desacuerdo
                    </label>
                </td>
              </tr>
            </tbody>
            
          </table>

				<center>
					<button type="submit" class="btn btn-primary btn-lg">
						Enviar
					</button>
				</center>
			{{Form::close()}}			
		</div>			
	</div>
</div>
@endif
@stop


