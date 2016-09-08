<?php

class AdminDatesTrainingController extends AdminBaseController
{
	public function index()
	{
		$dates_training = DateTraining::get();
		return View::make('admin::dates_training.index')->withDatesTraining($dates_training);
	}
	
	public function store()
	{
		$date_training = new DateTraining(Input::all());
		//Usuarios de corporativo
		$users = User::where('role','training')->get();
		$comments = Input::get('comments');

		foreach ($users as $user) {
			Mail::send('admin::email_templates.date_training_message',['user' => $user,'comments' => $comments],function($message) use ($user){
      			$message->to($user->email)->subject("Aviso,productos, insumos estrategicos.");
				//$message->to('jona_54_.com@hotmail.com')->subject("Aviso,productos, insumos estrategicos.");
    		});	
		}

		if($date_training->save())

			return Redirect::action('AdminDatesTrainingController@index')
				->withSuccess('Se ha agregado la fecha exitosamente.');
		else
			return Redirect::action('AdminDatesTrainingController@index')
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
		 $divisional = DB::table('dates_training')->whereId($id)->delete();

      return Redirect::to(action('AdminDatesTrainingController@index'))->withSuccess('Se ha eliminado la fecha.');	
    
	}

}