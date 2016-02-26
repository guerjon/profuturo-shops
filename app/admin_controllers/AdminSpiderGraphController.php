<?php

class AdminSpiderGraphController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{	
		$consultores = User::where('role','manager')->lists('nombre','id');
		
		return View::make('admin::spider_graph.index')->withConsultores($consultores);
	}


}
