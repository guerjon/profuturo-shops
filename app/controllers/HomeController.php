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

	public function getCarrito()
	{
		$access = false;
		$divisional_id = Auth::user()->divisional ? Auth::user()->divisional->id : 0;

		$dates = DB::table('divisionals_users')
			->where('divisional_id',$divisional_id)
			->where('from','<=',\Carbon\Carbon::now())
			->where('until','>=',\Carbon\Carbon::now());

		if ($dates->count() > 0){
			$access = true;
		}else{
			$access = false;
		}

		$last_order = DB::table('users')
				->join('divisionals_users','divisionals_users.divisional_id','=','users.divisional_id')
				->join('orders','orders.user_id','=','users.id')
				->where('orders.created_at','>=','divisionals_users.from')
				->where('orders.created_at','<=','divisionals_users.until');
				

		if ($last_order->count() > 0){
			$last_order = false;
		}else{
			$last_order = true;
		}		

		return View::make('pages.cart')->withLastOrder($last_order)
									   ->withAccess($access);
	}

	public function getCarritoMuebles()
	{
		return View::make('pages.cart_furniture')->withLastOrder(Auth::user()->furnitureOrders()->orderBy('created_at', 'desc')->first());
	}
}
