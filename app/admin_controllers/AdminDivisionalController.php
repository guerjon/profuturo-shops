<?php

class AdminDivisionalController extends BaseController{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$divisionales_uno = DB::table('divisionals_users')->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
																									->where('divisionals_users.divisional_id','1');
    
		$divisionales_dos = DB::table('divisionals_users')->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
																									->where('divisionals_users.divisional_id','2');

		$divisionales_tres = DB::table('divisionals_users')->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
																									->where('divisionals_users.divisional_id','3');
    
		$divisionales_cuatro = DB::table('divisionals_users')->select(DB::raw('divisionals_users.from as DESDE,divisionals_users.until as HASTA,divisionals_users.id'))
																									->where('divisionals_users.divisional_id','4');																									

		return View::make('admin::divisionales.index')->withDivisionalesUno($divisionales_uno->get())
																									->withDivisionalesDos($divisionales_dos->get())
																									->withDivisionalesTres($divisionales_tres->get())
																									->withDivisionalesCuatro($divisionales_cuatro->get());
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

		DB::table('divisionals_users')->insert(['from' => Input::get('from'),
																					 'until' => Input::get('until'),
																					 'divisional_id' => Input::get('divisional_id')]);

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