<?php

class AuthController extends BaseController
{

  public function getLogin()
  {
    return View::make('auth.login');
  }

  public function postLogin()
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
        return Redirect::to(action('AuthController@getLogin'))->withErrors('El usuario o contraseña son invalidos', 'login');
      }

    }else{
      return Redirect::to(action('AuthController@getLogin'))->withErrors($validator->messages(), 'login');
    }
  }

  public function getLogout()
  {
    Auth::logout();
    return Redirect::to(action('AuthController@getLogin'))->withInfo('Se ha cerrado su sesión');
  }
}
