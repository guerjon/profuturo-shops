<?php

/**
* 
*/
class FurnitureRequestsController extends BaseController
{
	
	public function index()
	{
		$requests = Auth::user()->furnitureOrders()->where('request','1')->get();

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
			
			$request_description = Input::get('request_description');
			$request_price = Input::get('request_price');
			$request_comments = Input::get('request_comments');
			$request_quantity = Input::get('request_quantiy');

			for ($i=0; $i < sizeof($request_description); $i++) { 
				$furniture_order->furnitures()->attach(10000,[
					'request_price' => $request_description[$i],
					'request_description' => $request_price[$i],
					'request_quantiy_product' => $request_quantity[$i],
					'request_comments' => $request_comments[$i]
				]);					
			}			
		}
		  
		return Redirect::action('FurnitureRequestsController@index')->withSuccess('Se añadio su solicitud general');

	}
}