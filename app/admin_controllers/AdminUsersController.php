<?php

class AdminUsersController extends AdminBaseController
{


  public function index()
  {
    return View::make('admin::users.index')
      ->withUsers(User::whereIn('role', ['user_requests', 'user_paper'])->orderBy('role')->orderBy('last_name')->get())
      ->withAdmins(User::whereIn('role', ['admin', 'manager'])->orderBy('role')->orderBy('last_name')->get());
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
}
