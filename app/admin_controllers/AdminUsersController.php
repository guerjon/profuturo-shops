<?php

class AdminUsersController extends AdminBaseController
{


	public function index()
	{
		$active_tab = Input::get('active_tab','admin');

		$users = User::withTrashed()->where('role',$active_tab);

		if (Input::has('ccosto')) {
			$users->where('ccosto','like','%'.Input::get('ccosto').'%');
		}

		if (Input::has('num_empleado')) {
			$users->where('num_empleado','like','%'.Input::get('num_empleado').'%');
		}

		if (Input::has('gerencia')) {
			$users->where('gerencia','like','%'.Input::get('gerencia').'%');
		}

		return View::make('admin::users.index')
			->withUsers($users->paginate(20))
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
				break;
			case 'user_training':
				$user->role = 'user_training';
				break;
			case 'user_system':
				$user->role = 'user_system';
				break;
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


	private	function getFilters($users,$input){

		if($emp_number = @$input['employee_number']){
				$users->where('ccosto', 'LIKE', "%{$emp_number}%");
		}
		if($gerencia = @$input['gerencia']){
				$users->where('gerencia', 'LIKE', "%{$gerencia}%");
		}		
		if ($ccosto = @$input['ccosto']) {
			$users->where('ccosto','LIKE',"%{$ccosto}%");
		}
	}

}
