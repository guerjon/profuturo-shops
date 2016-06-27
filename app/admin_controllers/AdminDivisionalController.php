<?php

class AdminDivisionalController extends BaseController{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($active_tab = null)
	{
		
		if(!$active_tab)
			$active_tab = Input::get('active_tab');

		$divisionals_date = DB::table('divisionals_users')
								->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
								->where('divisionals_users.divisional_id',$active_tab);
		
		return View::make('admin::divisionales.index')
			->withDivisionals(Divisional::orderBy('id')->get())
			->withDivisionalsToSelect(Divisional::orderBy('id')->lists('name','id'))
			->withdivisionalsDate($divisionals_date->get())
			->withActiveTab($active_tab);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$active_tab = Input::get('active_tab',1);
		DB::table('divisionals_users')
			->insert([
						'from' => Input::get('from'),
						'until' => Input::get('until'),
						'divisional_id' => $active_tab,
						'user_id' => 1
					]);

	 	return Redirect::action('AdminDivisionalController@index',['active_tab' => $active_tab])
	 			->withSuccess('Se ha agregado la fecha a la divisional.');	
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$divisional = DB::table('divisionals_users')
						->whereId($id)
						->delete();

		$active_tab = Input::get('active_tab');
      	return Redirect::action('AdminDivisionalController@index',['active_tab' => $active_tab])
      			->withSuccess('Se ha eliminado la fecha de la divisional.');	
    
	}


}