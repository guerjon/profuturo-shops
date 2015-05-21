<?

class GeneralRequestsController extends BaseController{

  public function index()
  {
    $requests = Auth::user()->generalRequests()->orderBy('rating');
    return View::make('general_requests.index')->withRequests($requests);
  }

  public function store()
  {
    $request = new GeneralRequest(Input::except('budget','quantity','unit_price','name'));
    Auth::user()->generalRequests()->save($request);

    $quantities = Input::get('quantity');
    $unit_prices= Input::get('unit_price');
    $name       = Input::get('name');
    $products = sizeof($quantities) - 1;

    while($products != -1){
      
      $general = new GeneralRequestProduct();
      
      $general->general_request_id = $request->id;
      $general->quantity = $quantities[$products];
      $general->unit_price = $unit_prices[$products];
      $general->name = $name[$products];
      if($general->name){
        $general->save();
      }
      $products--;
    }
    return Redirect::to(action('GeneralRequestsController@index'))->withSuccess("Se ha guardado su solicitud con id {$request->id}");
  }

  public function update($inutilizado){
    $status = Input::get('status');
    $id = Input::get('request_id');
    $request = GeneralRequest::find($id);
    $request->status = $status;
    $request->save();

  return Redirect::to(action('UserRequestsController@getIndex'))->withSuccess("Se ha actualizado el estado de la solicitud");
  } 

}
