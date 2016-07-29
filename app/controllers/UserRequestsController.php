<?php

class UserRequestsController extends BaseController
{

    public function index()
    {
        $active_tab = Input::get('active_tab', 'all');
        $requests = GeneralRequest::where('manager_id',Auth::id())
            ->orderBy('created_at','desc');

            //dd(Auth::id());
        if($active_tab == 'in_process'){
            $requests->where('status','>',0)->where('status','<',10);

        }elseif($active_tab == 'concluded'){
            Log::debug($requests->get());
          $requests->where('status',10);

        }elseif($active_tab == 'canceled'){
          $requests->onlyTrashed();
        }

        return View::make('general_requests.index')
            ->withRequests($requests->get())
            ->withActiveTab($active_tab);
    }

}
?>