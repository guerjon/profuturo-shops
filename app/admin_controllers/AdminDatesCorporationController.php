<?php

class AdminDatesCorporationController extends AdminBaseController
{
	public function index()
	{
		$dates_corporation = DateCorporation::get();
		return View::make('admin::dates_corporation.index')->withDatesCorporation($dates_corporation);
	}
	
	public function store()
	{
		$date_corporation = new DateCorporation(Input::all());
		if($date_corporation->save())
			return Redirect::action('AdminDatesCorporationController@index')
				->withSuccess('Se ha agregado la fecha exitosamente.');
		else
			return Redirect::action('AdminDatesCorporationController@index')
				->withErrors(['errors' => ['Surgio un error a la hora de guardar la fecha intente mas tarde.']]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		 $divisional = DB::table('dates_corporation')->whereId($id)->delete();

      return Redirect::to(action('AdminDatesCorporationController@index'))->withSuccess('Se ha eliminado la fecha.');	
    
	}

}