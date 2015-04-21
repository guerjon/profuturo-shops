<?php

class AdminUsersController extends AdminBaseController
{


  public function index()
  {
    return View::make('admin::users.index')
      ->withUsers(User::withTrashed()->whereIn('role', ['user_requests', 'user_paper'])->orderBy('role')->orderBy('ccosto')->get())
      ->withAdmins(User::withTrashed()->whereIn('role', ['admin', 'manager'])->orderBy('role')->orderBy('gerencia')->get());
  }

  public function create()
  {
    return View::make('admin::users.create')->withUser(new User);
  }

  public function store()
  {
    $user = new User(Input::all());
    if($user->save()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha guardado el usuario correctamente. Ya puede iniciar sesión');
    }else{
      return View::make('admin::users.create')->withUser($user);
    }
  }

  public function edit($user_id)
  {
    $user = User::find($user_id);
    if(!$user){
      return Redirect::back()->withWarning('No se encontró el usuario o está deshabilitado');
    }
    return View::make('admin::users.create')->withUser($user);
  }

  public function update($user_id)
  {
    $user = User::find($user_id);
    if(!$user){
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se encontró el usuario o está deshabilitado');
    }
    $user->fill(Input::only(['gerencia', 'linea_negocio']));
    if($user->save()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha actualizado');
    }else{
      return Redirect::back()->withErrors($user->getErrors());
    }
  }

  public function destroy($user_id)
  {
    $user = User::withTrashed()->find($user_id);
    if(!$user){
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se encontró el usuario');
    }elseif($user->trashed()){
      $user->restore();
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha habilitado nuevamente el usuario');
    }elseif($user->delete()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se deshabilitado el usuario');
    }else{
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se pudo deshabilitar al usuario');
    }

  }
}
