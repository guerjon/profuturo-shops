<?php

class AuthController extends BaseController
{

  public function getLogin()
  {
    return View::make('auth.login');
  }

  public function getAdminLogin(){
    return View::make('auth.admin_login');
  }

  public function postLogin()
  {
    $password = Input::get('password');
    $ccosto = Input::get('ccosto');
    
    

    $validator_ccosto = Validator::make(
      ['password' => $password,'ccosto' => $ccosto],
      [
        'ccosto' => 'required',
        'password' => 'required'
      ]);

    if($validator_ccosto->passes()){
      if(Auth::attempt(['password' => $password,'ccosto' => $ccosto])){
        return Redirect::intended('/');
      }else{
        $user = User::where('num_empleado','=',$ccosto)->first();
        if($user){
          Auth::login($user);
          return Redirect::intended('/');
        }
      }
    }

  

      return Redirect::to(action('AuthController@getLogin'))->withErrors('El centro de costos o contraseña son invalidos', 'login');
  }


  public function postAdminLogin()
  {
    $credentials = Input::only(['password', 'email']);
    $validator = Validator::make(
      $credentials,
      [
        'email' => 'required|email',
        'password' => 'required'
      ]);

    if($validator->passes()){
      if(Auth::attempt($credentials)){
        return Redirect::intended('/');
      }else{
        return Redirect::to(action('AuthController@getAdminLogin'))->withErrors('El centro de costos o contraseña son invalidos', 'login');
      }

    }else{
      return Redirect::to(action('AuthController@getAdminLogin'))->withErrors($validator->messages(), 'login');
    }
  }



  public function getLogout()
  {
    Auth::logout();
    return Redirect::to(action('AuthController@getLogin'))->withInfo('Se ha cerrado su sesión');
  }
}
