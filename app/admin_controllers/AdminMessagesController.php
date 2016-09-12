<?php

class AdminMessagesController  extends AdminBaseController {

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


		return View::make('admin::messages/index')
			->withMessages($messages->orderBy('created_at','desc')->paginate(10))
			->withActiveTab($active_tab)
			->withInput(Input::flash());
	}

	public function create()
	{
		$active_tab = Input::get('active_tab','users');
		return View::make('admin::messages/create')
			->withMessage(new Message())
			->withActiveTab($active_tab);
	}

	public function store()
	{
		$active_tab = Input::get('active_tab','users');
		$receivers = Input::get('receivers',[]);
		$body = Input::get('body');

		switch ($active_tab) {
			case 'users':
					
				if($this->sendUserMessage(Input::get('receivers'),$body))
					return Redirect::action('AdminMessagesController@index')->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");

			case 'regions':

				if($this->sendRegionMessage(Input::get('receivers'),$body))
					return Redirect::action('AdminMessagesController@index')->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");

			case 'divisionals':

				if($this->sendDivisionalMessage(Input::get('receivers'),$body))
					return Redirect::action('AdminMessagesController@index')->withSuccess('El mensaje fue enviado con exito');
				else
					return Redirect::back()->withErrors("Algo salio mal");

			default:
				return Redirect::back()->withErrors("Algo salio mal");

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

		foreach ($divisionals as $divisional_id) {

			$divisional = Divisional::find($divisional_id);
			
			if(!$divisional)
				return false;
				
			$users = DB::table('users')->where('divisional_id',$divisional->id)->get();

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
