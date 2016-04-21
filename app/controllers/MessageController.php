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
				
				if($this->sendUserMessage(Input::get('users'),$body))
					return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");

				break;

			case 'divisional':

				if($this->sendDivisionalMessage(Input::get('divisionals'),$body))
					return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");
				
				break;

			case 'region':

				if($this->sendRegionMessage(Input::get('regions'),$body))
					return Redirect::back()->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");
					
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
				return false;
			}
		}

		return true;		
	}

	private function sendDivisionalMessage($divisionals,$body)
	{

		foreach ($divisionals as $divisional) {

			$divisional = Divisional::find($region_id);
			
			if(!$region)
				
			$users = DB::table('users')->where('region_id',$region->id)->get();

			foreach ($users as $user) {
				
				if($user){
					
					$message = new Message();
					$message->sendMessage(Auth::user()->id,$user->id,$body);
					
				}else{
					return false;
				}
			}

			
		}	
		return true;
	}

	private function sendRegionMessage($regions,$body)
	{
		foreach ($regions as $region_id) {

			$region = Region::find($region_id);

			if(!$region)
				return false;
			
			$users = DB::table('users')->where('region_id',$region->id)->get();

			foreach ($users as $user) {
				
				if($user){
					
					$message = new Message();
					$message->sendMessage(Auth::user()->id,$user->id,$body);
					
				}else{
					return false;
				}
			}
		}
		return true;
	}

}
