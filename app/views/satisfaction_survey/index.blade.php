@extends('layouts.master')

@section('content')
	
@if(isset($survey))
	<div class="alert alert-info">La encuesta ya se ha sido contestada. Gracias.</div>
@elseif($errors->count() > 0)
  <div class="alert alert-danger">No se encontro la solicitud general</div>
@else
   <div class="row" >
      <div class="col-xs-10  col-xs-offset-1">
          <div class="content">
              <div class="row margin-bottom-10">
                  <div class="col-xs-12">
                      <div class="graph-title text-center" style="background-color:#">
                          POR FAVOR RESPONDE LAS SIGUENTES PREGUNTAS
                      </div>
                  </div>
              </div>
              <div class="stack-content">
                  <div class="row" style="margin-top:5%;">
                    <div class="col-sm-12 ">
                      <div class="form-group">
                        {{Form::open([
                            'action' => ['SatisfactionSurveyController@postQuestions',$general_request->id],
                            'role' => 'form',
                        ])}}

                            <table class="table table-striped">

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
                                  <td>
                                    <label for="explain_1" class="radio-inline">¿Por qué?</label> 
                                    {{Form::textarea('explain_1',null,['class' => 'form-control','size' => '20x3','required'])}}
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
                                  <td>
                                    <label for="explain_2" class="radio-inline">¿Por qué?</label> 
                                    {{Form::textarea('explain_2',null,['class' => 'form-control','size' => '20x3','required'])}}
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
                                  <td>
                                    <label for="explain_3" class="radio-inline">¿Por qué?</label> 
                                    {{Form::textarea('explain_3',null,['class' => 'form-control','size' => '20x3','required'])}}
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
                                  <td>
                                    <label for="explain_4" class="radio-inline">¿Por qué?</label> 
                                    {{Form::textarea('explain_4',null,['class' => 'form-control','size' => '20x3','required'])}}
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    {{Form::label('question_four','¿Volverías a usar la plataforma para realizar nuevas solicitudes?')}}
                                  </td>
                                  <td>
                                        <label class="radio-inline">
                                          {{Form::radio('question_four','1',['default' => 'true'])}} Totalmente de acuerdo
                                        </label>
                                  </td>
                                  <td>
                                      <label for="" class="radio-inline">
                                          {{Form::radio('question_four','2')}}De acuerdo
                                      </label>
                                        
                                  </td>
                                  <td>
                                      <label for="" class="radio-inline">
                                        {{Form::radio('question_four','3')}}En desacuerdo  
                                      </label>      
                                  </td>
                                  <td>
                                      <label for="" class="radio-inline">
                                        {{Form::radio('question_four','4')}} Totalmente en desacuerdo
                                      </label>
                                  </td>
                                  <td>
                                    <label for="explain_5" class="radio-inline">¿Por qué?</label> 
                                    {{Form::textarea('explain_5',null,['class' => 'form-control','size' => '20x3','required'])}}
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
              </div>                        
          </div>
      </div>
    </div>
@endif
@stop
@section('script')
<link href="{{ asset('css/survey.css') }}" rel="stylesheet"/>
@endsection


