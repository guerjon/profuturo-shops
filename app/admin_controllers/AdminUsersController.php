<?php

class AdminUsersController extends AdminBaseController
{


  public function index()
  {
    Log::info(Input::get('active_tab'));

    
   
    $active_tab = Session::get('active_tab', Input::get('active_tab', 'admin'));

        $admins = User::withTrashed()->where('role', 'admin');
        if(Input::has('admin')){

          $input = Input::get('admin', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $admins->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
          }
        }

        $managers = User::withTrashed()->where('role', 'manager');
        if(Input::has('manager')){
           $input = Input::get('manager', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $managers->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
          }         
        }
        $user_requests = User::where('role', 'user_requests');
        if(Input::has('user_requests')){
           $input = Input::get('user_requests', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $user_requests->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
          }   
        }
        $users_paper = User::where('role', 'user_paper');
        if(Input::has('user_paper')){
           $input = Input::get('user_paper', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $users_paper->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
          }           
        }

    return View::make('admin::users.index')
      ->withAdmins($admins->paginate(10))
      ->withManagers($managers->paginate(10))
      ->withUsersRequests($user_requests->paginate(10))
      ->withUsersPaper($users_paper->paginate(10))
      ->withActiveTab($active_tab);
  }

  public function create()
  {
    $active_tab = Input::get('active_tab');

    $user_manager = User::where('role','=','manager')->lists('gerencia','id');
    $users_colors_id = User::whereNotNull('color_id')->lists('color_id');
    $colors = Color::all()->except($users_colors_id);
    $regions = Region::all()->lists('name','id');
    return View::make('admin::users.create')->withUser(new User)
                                            ->withColors($colors)
                                            ->withManager($user_manager)
                                            ->withRegions($regions)
                                            ->withActiveTab($active_tab);
  }

  public function store()
  {
    $user = new User(Input::except('password_confirmation'));
    $active_tab = Input::get('active_tab');

    switch ($active_tab) {
      case 'admin':     
        $user->role = 'admin';
        break;
      case 'manager':     
        $user->role = 'manager';
        break;      

      case 'user_requests':     
        $user->role = 'user_requests';
        break;

      case 'users_paper':     
        $user->role = 'user_paper';
        break;

        default:
        # code...
        break;
    }

    if(Input::get('num_empleado') == null){
      $user->num_empleado = null;
    }
    Log::info(Input::all());
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
    $regions = Region::all()->lists('name','id');
    if(!$user){
      return Redirect::back()->withWarning('No se encontró el usuario o está deshabilitado');
    }
    return View::make('admin::users.create')->withUser($user)->withColors($colors)->withRegions($regions);
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
