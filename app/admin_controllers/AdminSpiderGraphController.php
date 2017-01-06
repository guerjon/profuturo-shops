<?php

class AdminSpiderGraphController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{   
		if(Input::has("excel"))
		{
			return Response::download(storage_path()."/estadisticas_encuestas.xls");
		}

		return View::make('admin::spider_graph.index');
	}

}
