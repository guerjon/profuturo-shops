<?

class GeneralRequestsController extends BaseController{

  public function index()
  {
    return View::make('general_requests.index')->withRequests(Auth::user()->general_requests);
  }

  public function store()
  {
    GeneralRequest::create(Input::except('budget'));
    return Redirect::to(action('GeneralRequestsController@index'));
  }
}
