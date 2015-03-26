<?

class AdminGeneralRequestsManagerController extends AdminBaseController{

public function getManagers(){
    return View::make('admin::general_requests_manager.index')->withRequests(GeneralRequest::all())->withManagers(User::where('role', 'manager')->get())
    ->withAdmins(User::whereIn('role', ['manager'])->
      orderBy('role')->orderBy('gerencia')->get());
  }

}
