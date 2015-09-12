<?php

class AdminUsersController extends AdminBaseController
{


  public function index()
  {
    $active_tab = Session::get('active_tab', Input::get('active_tab', 'admin'));

        $admins = User::withTrashed()->where('role', 'admin')->paginate(25);
        if(Input::has('admin')){

        }
        $managers = User::withTrashed()->where('role', 'manager')->paginate(25);
        if(Input::has('manager')){
         
        }
        $users_requests = User::where('role', 'user_requests')->paginate(25);
        if(Input::has('user_requests')){
        
        }
        $user_paper = User::where('role', 'user_paper')->paginate(25);
        if(Input::has('user_paper')){
        
        }

    return View::make('admin::users.index')
      ->withAdmins($admins)
      ->withManagers($managers)
      ->withUsersRequests($users_requests)
      ->withUsersPaper($user_paper)
      ->withActiveTab($active_tab);
  }

  public function create()
  {
    $user_manager = User::where('role','=','manager')->lists('gerencia','id');
    $users_colors_id = User::whereNotNull('color_id')->lists('color_id');
    $colors = Color::all()->except($users_colors_id);
    $regions = Region::all()->lists('name','id');
    return View::make('admin::users.create')->withUser(new User)->withColors($colors)->withManager($user_manager)->withRegions($regions);
  }

  public function store()
  {
    $user = new User(Input::except('password_confirmation'));

    if(Input::get('num_empleado')==null){
      $user->num_empleado = null;
    }
    if($user->save()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha guardado el usuario correctamente. Ya puede iniciar sesión');
    }else{
      return View::make('admin::users.create')->withUser($user);
    }
  }

  public function edit($user_id)
  {
    $users_colors_id = User::whereNotNull('color_id')->lists('color_id');
    $colors = Color::all()->except($users_colors_id);
    $user = User::find($user_id);
    if(!$user){
      return Redirect::back()->withWarning('No se encontró el usuario o está deshabilitado');
    }
    return View::make('admin::users.create')->withUser($user)->withColors($colors);
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

  public function getImport()
  {
    return View::make('admin::users.import_create');
  }

  public function postImport()
  {

  }

}
