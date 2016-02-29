<?php

class AdminDivisionalController extends BaseController{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$divisionals_date = DB::table('divisionals_users')->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
			->where('divisionals_users.divisional_id',Input::get('active_tab', '2'));
		
		$active_tab = Session::get('active_tab', Input::get('active_tab', '2'));
		
		return View::make('admin::divisionales.index')
			->withDivisionals(Divisional::orderBy('id')->get())
			->withDivisionalsToSelect(Divisional::orderBy('id')->lists('name','id'))
			->withdivisionalsDate($divisionals_date->get())
			->withActiveTab($active_tab);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Log::info(Input::all());

		DB::table('divisionals_users')->insert(['from' => Input::get('from'),'until' => Input::get('until'),'divisional_id' => Input::get('divisional_id')]);

	 	return Redirect::to(action('AdminDivisionalController@index'))->withInfo('Se ha agregado la fecha a la divisional.');	
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		 $divisional = DB::table('divisionals_users')->whereId($id)->delete();

      return Redirect::to(action('AdminDivisionalController@index'))->withSuccess('Se ha eliminado la fecha de la divisional.');	
    
	}


}