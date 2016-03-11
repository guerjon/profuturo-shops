<?php


class SatisfactionSurveyController extends BaseController{

	
	public function getQuestions($id)
	{	
		$survey = SatisfactionSurvey::where('general_request_id',$id)->first();
		if($survey){
			$general_request = GeneralRequest::find($id);
			if($general_request){
				return View::make('satisfaction_survey.index')->withGeneralRequest($general_request)->withSurvey($survey);	
			}else{
				return View::make('satisfaction_survey.index')->withErrors('La solicitud general fue eliminada o desabilitada.');	
			}
			
		}else{
			$general_request = GeneralRequest::find($id);
			if($general_request){
				return View::make('satisfaction_survey.index')->withGeneralRequest($general_request);				
			}else{
				return View::make('satisfaction_survey.index')->withErrors('La solicitud general fue eliminada o desabilitada.');		
			}
			
		}

	}

	public function postQuestions($id)
	{	

		$survey = new SatisfactionSurvey(Input::all());
		
		$survey->general_request_id = $id;

		if($survey->save()){
			return Redirect::back()->withSuccess('La encuesta se guardo correctamente,gracias.')->withSatisfactionSurvey($survey);
		}else{
			return Redirect::back()->withErrors('La encuesta no se pudo guardar, intente mas tarde.');
		}
	}

	public function getIndex(){
		$requests = Auth::user()->generalRequests()->orderBy('rating')->get();

		return View::make('general_requests.index')->withRequests($requests);	

	}

	public function postSatisfactionSurvey(){
		
		if(Input::get('general_request_id') == 11){
			$requests = Auth::user()->generalRequests()->orderBy('rating')->get();
			return View::make('general_requests.index')->withRequests($requests)->withError('La encuesta ya habia sido contestada con anterioridad');			
		}else{
			$survey = new SatisfactionSurvey();
			$survey->question_one = Input::get('question_one');
			$survey->question_two = Input::get('question_two');
			$survey->question_three = Input::get('question_three');
			$survey->question_four = Input::get('question_four');
			$survey->question_five = Input::get('question_five');
			$survey->question_six = Input::get('question_six');
			$survey->general_request_id = Input::get('general_request_id');
			
			if($survey->save()){
			
				$requests = Auth::user()->generalRequests()->orderBy('rating')->get();
				$request = GeneralRequest::find(Input::get('general_request_id'));
    			$request->status = 11;
				$request->save();
				
	    		return View::make('general_requests.index')->withRequests($requests)->withSuccess('La encuesta se a guardado gracias por participar.');			
			}else{
				$requests = Auth::user()->generalRequests()->orderBy('rating')->get();
				return View::make('general_requests.index')->withRequests($requests)->withError('Algo ha salido mal');			
			}
		}	
	}	
}