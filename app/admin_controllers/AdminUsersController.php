<?php

class AdminUsersController extends AdminBaseController
{


  public function index()
  {
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
            if($gerencia  = @$input['gerencia']){
              $admins->where('gerencia', 'LIKE', "%{$gerencia}%");
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
            if($gerencia = @$input['gerencia']){
              $managers->where('gerencia', 'LIKE', "%{$gerencia}%");
            }

          }         
        }
        $user_requests = User::withTrashed()->where('role', 'user_requests');
        if(Input::has('user_requests')){
           $input = Input::get('user_requests', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $user_requests->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
              $user_requests->where('gerencia', 'LIKE', "%{$gerencia}%");
            }

          }   
        }
        $users_paper = User::withTrashed()->where('role', 'user_paper');
        if(Input::has('user_paper')){
           $input = Input::get('user_paper', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
              $users_paper->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
              $users_paper->where('gerencia', 'LIKE', "%{$gerencia}%");
            }
          }
        }
        $users_furnitures = User::withTrashed()->where('role', 'user_furnitures');
        if(Input::has('user_furnitures'))
        {
          $input = Input::get('user_furnitures', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
                $users_furnitures->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
                $users_furnitures->where('gerencia', 'LIKE', "%{$gerencia}%");
            }

          }
        }

        $users_loader = User::withTrashed()->where('role', 'user_loader');
        if(Input::has('user_loader'))
        {
          $input = Input::get('user_loader', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
                $users_loader->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
                $users_loader->where('gerencia', 'LIKE', "%{$gerencia}%");
            }
          }
        }


        $users_mac = User::withTrashed()->where('role', 'user_mac');
        if(Input::has('user_mac'))
        {
          $input = Input::get('user_mac', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
                $users_mac->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
                $users_mac->where('gerencia', 'LIKE', "%{$gerencia}%");
            }
          }
        }

        $users_corporation = User::withTrashed()->where('role', 'user_corporation');
        if(Input::has('user_corporation'))
        {
          $input = Input::get('user_corporation', []);
          if(!is_array($input)){
            Log::warning("I did not receive an array");
          }else{
            if($emp_number = @$input['employee_number']){
                $users_corporation->where('ccosto', 'LIKE', "%{$emp_number}%");
            }
            if($gerencia = @$input['gerencia']){
                $users_corporation->where('gerencia', 'LIKE', "%{$gerencia}%");
            }
          }
        }


    return View::make('admin::users.index')
      ->withAdmins($admins->paginate(10))
      ->withManagers($managers->paginate(10))
      ->withUsersRequests($user_requests->paginate(10))
      ->withUsersPaper($users_paper->paginate(10))
      ->withUsersFurnitures($users_furnitures->paginate(10))
      ->withUsersLoader($users_loader->paginate(10))
      ->withUsersMac($users_mac->paginate(10))
      ->withUsersCorporation($users_corporation->paginate(10))
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
    $user->region_id = Input::get('region_id');

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

      case 'user_furnitures':     
        $user->role = 'user_furnitures';
        break;

      case 'user_loader':     
        $user->role = 'user_loader';
        break;
      case 'user_mac':     
        $user->role = 'user_mac';
        break;
      case 'user_corporation':
        $user->role = 'user_corporation';
        default:        
          break;
    }

      

    if(Input::get('num_empleado') == null){
      $user->num_empleado = null;
    }
    
    if($user->save()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha guardado el usuario correctamente. Ya puede iniciar sesión');
      
    }else{
      return Redirect::to(action('AdminUsersController@index'))->withErrors($user->getErrors());
    }
  }

  public function edit($user_id)
  {
    $active_tab = Input::get('active_tab');
    $users_colors_id = User::whereNotNull('color_id')->lists('color_id');
    $colors = Color::all()->except($users_colors_id);
    $user = User::find($user_id);
    $regions = Region::all()->lists('name','id');
    if(!$user){
      return Redirect::back()->withWarning('No se encontró el usuario o está deshabilitado.');
    }
    return View::make('admin::users.create')->withUser($user)
                                            ->withColors($colors)
                                            ->withRegions($regions)
                                            ->withActiveTab($active_tab);
  }

  public function update($user_id)
  {
    
    $user = User::find($user_id);
    $user->divisional_id = Input::get('divisional_id');
    if(!$user){
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se encontró el usuario o está deshabilitado.');
    }
    $user->fill(Input::except('password_confirmation'));
    if($user->save()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha actualizado el usuario.');
    }else{
      return Redirect::back()->withErrors($user->getErrors());
    }
  }

  public function destroy($user_id)
  {
    $user = User::withTrashed()->find($user_id);
    if(!$user){
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se encontró el usuario.');
    }elseif($user->trashed()){
      $user->restore();
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se ha habilitado nuevamente el usuario.');
    }elseif($user->delete()){
      return Redirect::to(action('AdminUsersController@index'))->withSuccess('Se deshabilitado el usuario.');
    }else{
      return Redirect::to(action('AdminUsersController@index'))->withWarning('No se pudo deshabilitar al usuario.');
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
