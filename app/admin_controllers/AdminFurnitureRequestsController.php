<?

/**
* 
*/
class AdminFurnitureRequestsController extends AdminBaseController
{
	
	public function index()
	{
		$requests = FurnitureOrder::where('request','1')->get();
		return View::make('admin::furnitures/requests')->withRequests($requests);
	}

	public function show($request_id)
	{
		$request = FurnitureOrder::with('furnitures','user')->find($request_id);

		if(!$request)
			return Redirect::back()->withErrors('No se encontro la solicitud');
		
		return View::make('admin::furnitures/resquests_show')->withRequest($request);
	}	

  /*
  *Este metodo actualiza la tabla furniture_furniture_id no es como tal un metodo de actualizacion
  * de furnitures_order
  */
  public function update($request_id)
  {
  	
  	$furniture_order = FurnitureOrder::find($request_id);
  	$is_edit = Input::get('is_edit');
    $user = $furniture_order->user;
    $email = $furniture_order->user->email;

  	if(!$furniture_order)
		        return Redirect::back()->withErrors('No se encontro la orden');
	  
    //Eliminamos todos los productos anteriores y agregamos los nuevos actualizados a la orden.  
  	if($is_edit)
  		DB::table('furniture_furniture_order')->whereIn('request_product_id',Input::get('request_product_id'))->delete();
  	
    $furniture_order->status = 1;
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

    }else{
      return Redirect::action('AdminFurnitureRequestsController@index')
      ->withErrores('Se ha producido un error a la hora de guardar la solicitud.');
    }	
    
    if($is_edit) 
      $message = "Se actualizaron los productos seleccionados para la solicitud: " + $furniture_order->id; 
    else
      $message = "Se cotizaron los procutos para la solicitud: " + $furniture_order->id; 

    Mail::send('admin::email_templates.furniture_request',
        [
          'user' => $user,
          'furniture_order' => $furniture_order,
          'furniture_order' => $furniture_order->furnitures,
        ],
        function($message) use ($email,$message){
              $message->to($email)->subject($message);
        }
    );  	  

    return Redirect::action('AdminFurnitureRequestsController@index')->withSuccess('Se actualizado la solicitud.');

  }
}