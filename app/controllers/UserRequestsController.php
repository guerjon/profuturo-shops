<?

class UserRequestsController extends BaseController
{

  public function getIndex()
  {
    $active_tab = Input::get('active_tab', 'assigned');
    $assigneds = ['ASIGNADO','NO ASIGNADO'];
    $active_category = ['ASIGNADO','NO ASIGNADO'];


    return View::make('general_requests.index')->withRequests(Auth::user()->assignedRequests()->orderBy('created_at','desc')->get())
    												  ->withAssigneds($assigneds)
    												  ->withActiveCategory($active_category)
    												  ->withActiveTab($active_tab);
  }

}
