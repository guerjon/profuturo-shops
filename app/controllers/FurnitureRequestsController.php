<?php

/**
* 
*/
class FurnitureRequestsController extends BaseController
{
	
	public function index()
	{
		$requests = Auth::user()
			->furnitureOrders()
			->where('furniture_orders.request',1)
			->join('furniture_furniture_order','furniture_orders.id','=','furniture_furniture_order.furniture_order_id')
			->groupBy('furniture_orders.id')
			->get();

		return View::make('furnitures/requests')
			->withRequests($requests);
	}

	public function create()
	{
		return View::make('furnitures/requests_create');
	}

	public function store()
	{
		$user = Auth::user();
		$furniture_order = new FurnitureOrder(['user_id' => $user->id,'request' => '1']);
		
		if($furniture_order->save())
		{
			
			$request_price = Input::get('request_price');
			$request_description = Input::get('request_description');
			$request_quantiy_product = Input::get('request_quantiy_product');
			$request_comments = Input::get('request_comments');

			for ($i=0; $i < sizeof($request_description); $i++) { 
				$furniture_order->furnitures()->attach(10000,[
					'request_price' => $request_price[$i],
					'request_description' => $request_description[$i],
					'request_quantiy_product' => $request_quantiy_product[$i],
					'request_comments' => $request_comments[$i]
				]);					
			}			
		}
		  
		return Redirect::action('FurnitureRequestsController@index')->withSuccess('Se añadio su solicitud.');

	}

	public function show($request_id)
	{
		$request = FurnitureOrder::with('furnitures')->find($request_id);

		if(!$request)
			return Redirect::back()->withErrors('No se encontro la solicitud');
		
		return View::make('furnitures/request_show')->withRequest($request);

	}
}