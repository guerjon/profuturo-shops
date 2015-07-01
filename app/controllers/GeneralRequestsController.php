<?

class GeneralRequestsController extends BaseController{

  public function index()
  {
    $requests = Auth::user()->generalRequests()->orderBy('rating')->get();
    return View::make('general_requests.index')->withRequests($requests);
  }

  public function store()
  {
    $request = new GeneralRequest(Input::except('budget','quantity','unit_price','name'));
    /*agregando el valor adicional a los campos*/
    $request->employee_name = Auth::user()->nombre;
    $request->employee_email = Auth::user()->email;
    $request->employee_cellphone = Auth::user()->celular;
    $request->employee_number = Auth::user()->num_empleado;
    $request->employee_ext = Auth::user()->extension;

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

    $email_info = ['user' => Auth::user()];
    Mail::send('admin::email',$email_info,function($message){
      $message->to('jona_54_.com@ciencias.unam.mx','JONGUER2')->subject('HolaMundo');
      Log::info('paso por aqui -------------');
    });
    
      
    $request->save();

  return Redirect::to(action('UserRequestsController@getIndex'))->withSuccess("Se ha actualizado el estado de la solicitud");
  } 

}
