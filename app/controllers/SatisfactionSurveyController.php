<?php


class SatisfactionSurveyController extends BaseController{


			public function getIndex(){
				return View::make('general_requests.index');	
			}
	
			public function postSatisfactionSurvey(){
				
				if(Input::get('general_request_id') == 10){
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
		    		$request->status = 10;
						$request->save();
						
		    		return View::make('general_requests.index')->withRequests($requests)->withSuccess('La encuesta se a guardado gracias por participar.');			
					}else{
						return View::make('general_requests.index')->withRequests($requests)->withError('Algo ha salido mal');			
					}
				}
				
			}
}