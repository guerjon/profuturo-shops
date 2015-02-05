<?

class AdminGeneralRequestsController extends AdminBaseController{

  public function index(){
    return View::make('admin::general_requests.index')->withRequests(GeneralRequest::all())->withManagers(User::where('role', 'manager')->get());
  }
}
