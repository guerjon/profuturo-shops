<?

class GeneralRequestsController extends BaseController{

  public function index()
  {
    return View::make('general_requests.index')->withRequests(Auth::user()->general_requests);
  }

  public function store()
  {
    $request = new GeneralRequest(Input::except('budget'));
    Auth::user()->generalRequests()->save($request);
    return Redirect::to(action('GeneralRequestsController@index'))->withSuccess("Se ha guardado su solicitud con id {$request->id}");
  }
}
