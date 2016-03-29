<?php

class MessageController extends \BaseController {

	public function store()
	{
		$users = Input::get('users');
		$message_body = Input::get('mensaje');
		$count = 0;

		foreach ($users as $id) {
			$user = User::find($id);
			if($user){
				$values = [ 'sender_id' => Auth::user()->id,
	                'receiver_id' => $user->id,
	                'body' => $message_body,
	                'type' => 'personal'
              	];

				$message = new Message($values);

				if($message->save()){
					$user->messages()->attach($message->id);
					$count++;
				}
			}else{
				return Redirect::back()->withErrors('No se encontro el usuario.');
			}
		}

		if($count == sizeof($users)){
			return Redirect::back()->withSuccess('El mensaje fue enviado con exito a los usuarios');
		}else{
			return Redirect::back()->withErrors('El mensaje no pudo ser enviado en su totalidad.');
		}
	}
}
