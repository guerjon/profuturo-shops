<?php

class MessagesController extends \BaseController {

	public function index()
	{
		$active_tab = Input::get('active_tab','received');

		$messages = Message::query();
		$user = Auth::user();

		
		if($active_tab == 'received')
			$messages->where('receiver_id',$user->id);
		else
			$messages->where('sender_id',$user->id);

        foreach ($messages->get() as $message){
          $message->update(['read_at' => \Carbon\Carbon::now()]);
        }

		return View::make('messages/index')
			->withMessages($messages->orderBy('created_at','desc')->paginate(10))
			->withActiveTab($active_tab)
			->withInput(Input::flash());

	}

	public function store()
	{
		$body = Input::get('body');

		if($this->sendUserMessage(Input::get('receivers'),$body))
			return Redirect::action('MessagesController@index')->withSuccess('El mensaje fue enviado con exito');
		else
			return Redirect::back()->withErrors("Algo salio mal");
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

}