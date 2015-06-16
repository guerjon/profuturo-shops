<?

class AdminGeneralRequestsController extends AdminBaseController{

  public function index(){
  	$request = GeneralRequest::select('*');
  	if(Input::has('user_id')){
  		
       $request->where('user_id',Input::get('user_id'));
  	}
  	if(Input::has('month')){
  		$request->where(DB::raw('MONTH(created_at)'), Input::get('month'));	
  	}

  	if (Input::has('year')) {
  		$request->where(DB::raw('YEAR(updated_at)'), Input::get('year'));
  	}

    return View::make('admin::general_requests.index')
    ->withRequests($request->get())
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