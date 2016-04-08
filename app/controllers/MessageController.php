<?php

class MessageController extends \BaseController {

	public function store()
	{
		$users = Input::get('users');
		Log::debug(Input::all());
		$body = Input::get('mensaje');
		
		$type = Input::get('message_type');

		switch ($type) {
			case 'user':
					$users = Input::get('users');
					$this->sendUserMessage($users,$body);
				break;
			case 'divisional':
					$divisionals = Input::get('divisionals');
					$this->sendDivisionalMessage($divisionals,$body);
				break;
			case 'region':
					$region = Input::get('regions');
					$this->sendRegionMessage($region,$body);
				break;
		}
	}

	private function sendUserMessage($users,$body)
	{
		foreach ($users as $user_id) {
			$user = User::find($user_id);
			if($user){
				$values = [ 'sender_id' => Auth::user()->id,
	                'receiver_id' => $user->id,
	                'body' => $body,
	                'type' => 'personal'
              	];

				$message = new Message($values);

				if($message->save()){
					$user->messages()->attach($message->id);
				}
			}else{
				return Redirect::back()->withErrors('No se encontro el usuario.');
			}
		}

		
			return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
		
	}

	private function sendDivisionalMessage($divisionals,$body)
	{

		foreach ($divisionals as $divisional_id) {
			$divisional = Divisional::find($divisional_id);
			
			if(!$divisional)
				return Redirect::back()->withErrors("No se encontro la region");

			$users = $divisional->users;
			
			foreach ($users as $user_id) {

				$user = User::find($user_id);
				if($user){
					$values = [ 'sender_id' => Auth::user()->id,
		                'receiver_id' => $user->id,
		                'body' => $body,
		                'type' => 'personal'
	              	];

					$message = new Message($values);

					if($message->save()){
						$user->messages()->attach($message->id);
					}
				}else{
					return Redirect::back()->withErrors('No se encontro el usuario.');
				}
			}

			return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
			
		}		
	}

	private function sendRegionMessage($regions,$body){
		foreach ($regions as $region_id) {

			$region = Region::find($region_id);
			
			if(!$region)
				return Redirect::back()->withErrors("No se encontro la region");
			
			$users = $region->users;
			Log::debug('?????????????????????????????');
			Log::debug($users);
			Log::debug('?????????????????????????????');

			foreach ($users as $id) {
				$user = User::find($id);
				if($user){
					$values = [ 'sender_id' => Auth::user()->id,
		                'receiver_id' => $user->id,
		                'body' => $body,
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

			return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
			
		}
	}

}
