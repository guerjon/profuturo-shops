<?php

class AdminBaseController extends BaseController
{

  public function __construct()
  {
    $this->beforeFilter('auth');
    $this->beforeFilter(function(){
      if(!Auth::user()->is_admin){
        if(Request::ajax()){
          return Response::json([
            'status' => 403
            ]);
        }else{
          return Redirect::to(URL::previous())->withWarning('Usted no tiene permiso para entrar a esta sección.');
        }
      }
    });
  }
}
