<?

class UrgentRequestsController extends BaseController
{

  public function getIndex()
  {
    $now = \Carbon\Carbon::now();
    $now->addDays(7);
    $request = Auth::user()->generalRequestsByManager()->where('deliver_date','<=',$now->format('Y-m-d'))->orderBy('rating')->get();
    return View::make('urgent_requests.index')->withRequests($request);
  }



}
