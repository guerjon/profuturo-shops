<div class="modal fade" id="request-modal-satisfaction-survey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignar un asesor al proyecto</h4>
      </div>
      <div class="modal-body">

        {{ Form::open([
            'action' => 'SatisfactionSurveyController@postSatisfactionSurvey',
            'class' => 'form-horizontal',
            'id' => 'assign-form'
        ])}}
           
             
          <input type="hidden" name="general_request_id" id="general_request_id">
          
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
                  {{Form::label('question_one','Implementación de estrategias y proactividad para identificar soluciones')}}
                </td>
                <td>
                  <label class="radio-inline">
                    {{Form::radio('question_one','1')}} 1
                  </label>
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                      {{Form::radio('question_one','2')}}2
                  </label>
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','3')}}3   
                  </label>                      
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','4')}} 4
                  </label>                      
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','5')}} 5
                  </label>
                </td>
                <td>
                  <label for="question_one" class="radio-inline">
                    {{Form::radio('question_one','6')}}6               
                  </label>
                </td>     
              </tr>
              <tr>
                <td>
                  {{Form::label('question_two','Adecuación y control al proceso de Papelería')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_two','1')}} 1
                      </label>
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                        {{Form::radio('question_two','2')}}2
                    </label>
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','3')}}3   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','4')}} 4
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','5')}} 5
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_two" class="radio-inline">
                      {{Form::radio('question_two','6')}}6               
                    </label>
                </td>             
              </tr>
              <tr>
                <td>
                  {{Form::label('question_three','Cumplimiento del proceso de papelería 3')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_three','1')}} 1
                      </label>
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                        {{Form::radio('question_three','2')}}2
                    </label>
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','3')}}3   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','4')}} 4
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','5')}} 5
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_three" class="radio-inline">
                      {{Form::radio('question_three','6')}}6               
                    </label>
                </td>         </tr>
              <tr>
                <td>
                  {{Form::label('question_four','Evaluación mensual de proveedores')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_one','1')}} 1
                      </label>
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                        {{Form::radio('question_four','2')}}2
                    </label>
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','3')}}3   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','4')}} 4
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','5')}} 5
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_four" class="radio-inline">
                      {{Form::radio('question_four','6')}}6               
                    </label>
                </td>     
              </tr>
              <tr>
                <td>
                  {{Form::label('question_five','Encuesta de calidad de usuarios /actitud de servicio')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_five','1')}} 1
                      </label>
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                        {{Form::radio('question_five','2')}}2
                    </label>
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','3')}}3   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','4')}} 4
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','5')}} 5
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_five" class="radio-inline">
                      {{Form::radio('question_five','6')}}6               
                    </label>
                </td>                 
              </tr>
              <tr>
                <td>
                  {{Form::label('question_six','Proactiviad con proveedores: Búsqueda, Conocimiento, Negociación, Comunicación, Seguimiento ')}}
                </td>
                <td>
                      <label class="radio-inline">
                        {{Form::radio('question_six','1')}} 1
                      </label>
                </td>
                <td>
                    <label for="question_six" class="radio-inline">
                        {{Form::radio('question_six','2')}}2
                    </label>
                      
                </td>
                <td>
                    <label for="question_six" class="radio-inline">
                      {{Form::radio('question_six','3')}}3   
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_six" class="radio-inline">
                      {{Form::radio('question_six','4')}} 4
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_six" class="radio-inline">
                      {{Form::radio('question_six','5')}} 5
                    </label>

                      
                      
                </td>
                <td>
                    <label for="question_six" class="radio-inline">
                      {{Form::radio('question_six','6')}}6               
                    </label>
                </td>     
              </tr>
            </tbody>
            
          </table>
        
        {{ Form::close()}}                
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" id="submit-btn">Enviar</button>
      </div>
    </div>
  </div>
</div>

