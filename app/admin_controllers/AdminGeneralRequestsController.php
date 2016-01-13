<?

class AdminGeneralRequestsController extends AdminBaseController{

  public function index(){
  	$request = GeneralRequest::select('*');

    $active_tab = Input::get('active_tab', 'assigned');
  
    if($active_tab == 'assigned'){
        $request->whereNotNull('manager_id');
    }else{
      $request->whereNull('manager_id');
    }

  	if(Input::has('user_id')){
  		
       $request->where('user_id',Input::get('user_id'));
  	}
  	if(Input::has('month')){
  		$request->where(DB::raw('MONTH(created_at)'), Input::get('month'));	
  	}

  	if (Input::has('year')) {
  		$request->where(DB::raw('YEAR(updated_at)'), Input::get('year'));
  	}
    $assigneds = ['ASIGNADO','NO ASIGNADO'];

    $active_category = ['ASIGNADO','NO ASIGNADO'];
    return View::make('admin::general_requests.index')->withAssigneds($assigneds)->withActiveCategory($active_category)
    ->withRequests($request->orderBy('created_at','desc')->orderBy('rating','desc')->paginate(10))->withActiveTab($active_tab)
    ->withUsers(User::where('role', 'user_requests')->lists('gerencia','id'));
  }


  public function show($id)
  {
    $general_request = GeneralRequest::find($id);
    if($general_request){
      return View::make('admin::general_requests.show')->withGeneral($general_request);   
    }else{
      return Redirect::back()->withWarning('No se encontró la orden');
    }


       
  }

}