<?

class AdminGeneralRequestsAssignController extends AdminBaseController{

  public function getIndex(){
    return View::make('admin::general_requests_assign.index')->withRequests(GeneralRequest::orderBy(DB::raw('manager_id IS NULL'),'desc')->get())->withManagers(User::where('role', 'manager')
      ->orderBy('gerencia'));
  }

  public function postAssign(){
    $manager = User::find(Input::get('manager_id'));
    $request = GeneralRequest::find(Input::get('request_id'));
    if(!$manager or !$request){
      return Redirect::back()->withInfo('No se encontró el asesor o no se encontró la orden');
    }
    $request->manager()->associate($manager);
    $request->rating = Input::get('rating');
    if($request->save()){

    
    //$email = $manager->email;
    $email = 'jona_54_.com@hotmail.com';

    Mail::send('admin::email_templates.general_request_notice',['general_request' => $request],function($message) use ($email){
      $message->to($email)->subject("Solicitud general asignada.");
    });


      return Redirect::back()->withSuccess('Se ha asignado la solicitud');
    }else{
      return Redirect::back()->withErrors($request->getErrors());
    }
  }

}
