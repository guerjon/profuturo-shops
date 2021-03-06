<?php

class AdminGeneralRequestsAssignController extends AdminBaseController{

  public function getIndex(){
      return View::make('admin::general_requests_assign.index')
        ->withRequests(GeneralRequest::withTrashed()->where('status','!=','11')
        ->orderBy('created_at','desc')->orderBy(DB::raw('manager_id IS NULL'),'desc')->get())
        ->withManagers(User::where('role', 'manager')
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

      $email = $request->user->email;
      //$email = 'karina.ascencionhernandez@profuturo.com.mx';

      Mail::send('admin::email_templates.aviso_consultor',['general_request' => $request],function($message) use ($email){
        $message->to($email)->subject("Solicitud general asignada.");
      });
     

      $email = $manager->email;
      Mail::send('admin::email_templates.aviso_consultor',['general_request' => $request],function($message) use ($email){
        $message->to($email)->subject("Solicitud general asignada por Administrador.");
      });

  /*    $email = 'jona_54_.com@ciencias.unam.mx';

      Mail::send('admin::email_templates.aviso_consultor',['general_request' => $request],function($message) use ($email){
        $message->to($email)->subject("Solicitud general asignada por Administrador.");
      });
  */

      return Redirect::back()->withSuccess('Se ha asignado la solicitud');

    }else{
      return Redirect::back()->withErrors($request->getErrors());
    }
  }


  public function putUpdate($id)
  {
    $general_request = GeneralRequest::withTrashed()->find($id);
    if($general_request->restore()){
        return Redirect::action('AdminGeneralRequestsAssignController@getIndex')->withSuccess('Se restauro la orden exitosamente.');
    }else{
      return Redirect::back()->withErrors('Ocurrio un error al restaurar la orden');
    }
  }

  public function deleteDestroy($id)
  {

    $general_request = GeneralRequest::find($id);
    if($general_request){
      
      if($general_request->delete()){
        return Redirect::action('AdminGeneralRequestsAssignController@getIndex')->withSuccess('Se cancelo la orden exitosamente.');
      }else{
        return Redirect::back()->withErrors('No se pudo cancelar  la orden');
      }
      
    }else{
        return Redirect::back()->withErrors('No se encontro la orden');
    }
    
  }

}
?>