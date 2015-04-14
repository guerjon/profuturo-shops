<?

class GeneralRequestsController extends BaseController{

  public function index()
  {
    return View::make('general_requests.index')->withRequests(Auth::user()->generalRequests()->orderBy('rating'));
  }

  public function store()
  {
    $request = new GeneralRequest(Input::except('budget'));
    Auth::user()->generalRequests()->save($request);
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
