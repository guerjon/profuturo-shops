<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/



	public function getIndex()
	{
		$credentials = ['ccosto' => Auth::user()->ccosto,'password' => 'password']; 
		return View::make('hello')->withCredentials($credentials);
	}

	public function postPassword(){
		$email = Input::get('email');
	    $password = Input::get('password');

	    Auth::user()->email = $email;
	    Auth::user()->password = $password;
	    Auth::user()->save();
			$credentials = ['ccosto' => Auth::user()->ccosto,'password' => 'password']; 	
	    return View::make('hello')->withSuccess('Se ha guardado su información exitosamente')->withCredentials($credentials);
	}
	private function sumDay($date){
    	return \Carbon\Carbon::createFromFormat('Y-m-d',$date)->addDay()->format('Y-m-d');
  	} 

	public function getCarrito()
	{

		$access = false;
		$divisional_id = Auth::user()->divisional ? Auth::user()->divisional->id : 0;

		$dates = DB::table('divisionals_users')
			->where('divisional_id',$divisional_id)
			->where('kind', 'papeleria')
			->where('from','<=',\Carbon\Carbon::now()->format('Y-m-d'))
			->where('until','>=',\Carbon\Carbon::now()->format('Y-m-d'));
	
		$divisional = DB::table('divisionals_users')
			->where('divisionals_users.divisional_id',Auth::user()->divisional_id)
			->orderBy('created_at','desc')
			->first();

		if($divisional){
			$last_order = DB::table('users')
					->join('divisionals_users','divisionals_users.divisional_id','=','users.divisional_id')
					->join('orders','orders.user_id','=','users.id')
					->where('users.id',Auth::user()->id)
					->where('orders.created_at','>=',$divisional->from)
					->where('orders.created_at','<=',$this->sumDay($divisional->until))
					->whereNull('orders.deleted_at');	
		}else{
			$last_order = 1;			
		}

	
		// $access = ($dates->count() > 0) ? ($last_order->count() < 1) : false;
		$access = $dates->count() > 0;
		// $access = true;

		$user = User::where('ccosto',Auth::user()->ccosto)->first();
		
		return View::make('pages.cart')->withAccess($access)->withUser($user);
	}

	public function getCarritoMuebles()
	{
		return View::make('pages.cart_furniture')->withLastOrder(Auth::user()->furnitureOrders()->orderBy('created_at', 'desc')->first());
	}

	public function getCarritoMac()
	{
		$user = User::where('ccosto',Auth::user()->ccosto)->first();
		return View::make('pages.cart_mac')
		->withLastOrder(Auth::user()->MacOrders()->orderBy('created_at', 'desc')->first())
		->withUser($user);	
	}

	public function getCarritoCorporativo()
	{

		$user = User::where('ccosto',Auth::user()->ccosto)->first();

		$divisional_id = Auth::user()->divisional ? Auth::user()->divisional->id : 0;

		$dates = DB::table('dates_corporation')
			->where('kind', 'papeleria')
			->where('since','<=',\Carbon\Carbon::now()->format('Y-m-d'))
			->where('until','>=',\Carbon\Carbon::now()->format('Y-m-d'));
		$access = $dates->count() > 0;
		return View::make('pages.cart_corporation')
		->withAccess($access)
		->withLastOrder(Auth::user()->CorporationOrders()->orderBy('created_at', 'desc')->first())
		->withUser($user);	
	}

	public function getCarritoCapacitaciones()
	{

		$access = false;

		//checamos si hoy es un dia de pedido
		$dates = DB::table('dates_training')
			->where('since','<=',\Carbon\Carbon::now()->format('Y-m-d'))
			->where('until','>=',\Carbon\Carbon::now()->format('Y-m-d'));
			
		
		//Tomamos la ultima fecha anadida
		$divisional = DB::table('dates_training')
			->orderBy('created_at','desc')
			->first();

		if($divisional){

			$last_order = DB::table('users')
					->join('training_orders','training_orders.user_id','=','users.id')
					->where('users.id',Auth::user()->id)
					->where('training_orders.created_at','>=',$divisional->since)
					->where('training_orders.created_at','<=',$this->sumDay($divisional->until))
					->whereNull('training_orders.deleted_at');	

		}else{
			$last_order = 1;			
		}

		$access = true;

		$user = User::where('ccosto',Auth::user()->ccosto)->first();
		return View::make('pages/cart_training')
		->withLastOrder(Auth::user()->TrainingOrders()->orderBy('created_at', 'desc')->first())
		->withAccess($access)
		->withUser($user);			
	}

	public function getEmailTemplate()
	{	
		return View::make('email_templates/furnitere_orders');
	}

}
