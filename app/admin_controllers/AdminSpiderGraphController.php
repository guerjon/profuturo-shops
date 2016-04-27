
<?php

class AdminSpiderGraphController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{	
		$surveys = DB::table('satisfaction_surveys')->select(DB::raw('general_requests.id as id'))
	        ->join('general_requests','general_requests.id','=','satisfaction_surveys.general_request_id')  
	        ->join('users','users.id','=','general_requests.manager_id')->lists('id');

	    $surveys = GeneralRequest::has('satisfactionSurvey')->lists('id','id');

	    // if(Input::has('since'))
	    //   $surveys->where('general_requests.created_at','>',Input::get('since'));

	    // if(Input::has('until'))
	    //   $surveys->where('general_requests.created_at','<',Input::get('until'));

	    // if(Input::has('consultor'))
	    //   $surveys->where('users.id','=',Input::get('id'));

	    // if(Input::has('solicitud'))
	    //   $surveys->where('satisfaction_surveys.id','=',Input::get('solicitud'));



		return View::make('admin::spider_graph.index')->withSurveys($surveys);

	}


}
