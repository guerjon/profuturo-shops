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
			->where('from','<=',\Carbon\Carbon::now()->format('Y-m-d'))
			->where('until','>=',\Carbon\Carbon::now()->format('Y-m-d'));

		if ($dates->count() > 0){
			$access = true;
		}else{
			$access = false;
		}
		Log::info('El usuario tiene acceso por su divisional '.$dates->count());
		$last_order = DB::table('users')
				->join('divisionals_users','divisionals_users.divisional_id','=','users.divisional_id')
				->join('orders','orders.user_id','=','users.id')
				->where('users.id',Auth::user()->id)
				->where('orders.created_at','>=',DB::raw('divisionals_users.from'))
				->where('orders.created_at','<=',DB::raw('divisionals_users.until'));
						
				
				$last_order = $last_order->count();

		if($last_order > 0){
				$last_order = 0;
		}else{
				$last_order = 1;
		}		
		
		if ($last_order and $access){
			$access = true;
		}else{
			$access = true;
		}
		
		$address = Address::where('ccostos',Auth::user()->ccosto)->first();
		return View::make('pages.cart')->withAccess($access)->withAddress($address);
	}

	public function getCarritoMuebles()
	{
		return View::make('pages.cart_furniture')->withLastOrder(Auth::user()->furnitureOrders()->orderBy('created_at', 'desc')->first());
	}
}
