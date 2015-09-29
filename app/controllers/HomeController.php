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

		$dates = DB::table('divisionals_users')
			->where('divisional_id',Auth::user()->divisional->id)
			->where('from','<=',\Carbon\Carbon::now())
			->where('until','>=',\Carbon\Carbon::now());

		if ($dates->count() > 0){
			$access = true;
		}else{
			$access = false;
		}

		return View::make('pages.cart')->withLastOrder(Auth::user()->orders()->orderBy('created_at', 'desc')->first())
									   ->withAccess($access);
	}

	public function getCarritoMuebles()
	{
		return View::make('pages.cart_furniture')->withLastOrder(Auth::user()->furnitureOrders()->orderBy('created_at', 'desc')->first());
	}
}
