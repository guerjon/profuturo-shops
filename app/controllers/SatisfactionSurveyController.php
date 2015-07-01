<?php


class SatisfactionSurveyController extends BaseController{


			public function getIndex(){
				return View::make('satisfaction_survey.index');	
			}
	
			public function postSatisfactionSurvey(){
				$survey = new SatisfactionSurvey(Input::all());
				return Redirect::make(View::make('satisfaction_survey.thanks'))->withSuccess('Su encuent');
			}
}