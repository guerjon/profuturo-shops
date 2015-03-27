<?

class UserRequestsController extends BaseController
{

  public function getIndex()
  {
    return View::make('admin::general_requests.index')->withRequests(Auth::user()->assigned_requests);
  }
}
