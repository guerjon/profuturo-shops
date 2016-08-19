<?php

class AdminDatesMacController extends AdminBaseController
{
	public function index()
	{
		$dates_mac = DateMac::get();
		return View::make('admin::dates_mac.index')
			->withDatesMac($dates_mac);
	}
	
	public function store()
	{
		$date_mac = new DateMac(Input::all());
		//Usuarios de corporativo
		$users = User::where('role','mac')->get();
		$comments = Input::get('comments');

		foreach ($users as $user) {
			Mail::send('admin::email_templates.date_corporation_message',['user' => $user,'comments' => $comments],function($message) use ($user){
      			$message->to($user->email)->subject("Aviso,productos, insumos estrategicos.");
				//$message->to('jona_54_.com@hotmail.com')->subject("Aviso,productos, insumos estrategicos.");
    		});	
		}

		if($date_mac->save())

			return Redirect::action('AdminDatesMacController@index')
				->withSuccess('Se ha agregado la fecha exitosamente.');
		else
			return Redirect::action('AdminDatesMacController@index')
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
		 $divisional = DB::table('dates_mac')->whereId($id)->delete();

      return Redirect::to(action('AdminDatesMacController@index'))->withSuccess('Se ha eliminado la fecha.');	
    
	}

}