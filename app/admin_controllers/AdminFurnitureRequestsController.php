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

	//Furniture id corresponde a la columna request_product_id de la tabla furniture_furniture_order
	//y sera asignada a furniture_orders.product_request_selected como producto asignado
	public function update($furniture_id)
	{	
		$request = FurnitureOrder::find(Input::get('request_id'));
		
		if(!$request)
			return Redirect::back()->withErrors('No se encontro la orden');

		if($request->update(['status' => '1','product_request_selected' => $furniture_id]))
			return Redirect::action('AdminFurnitureRequestsController@index')
				->withSuccess('Se ha actualizado la solicitud con exito');
		else
			return Redirect::back()->withErrors($request->getErrors());
	}
}