<?
use \Carbon\Carbon;
class AdminGeneralRequestsController extends AdminBaseController{

	public function index($active_tab = null){
		if(!$active_tab)
			$active_tab = Input::get('active_tab', 'all');

		$request = GeneralRequest::select('general_requests.id as solicitud',
					'general_requests.project_title as titulo',
					DB::raw('general_request_products.unit_price * general_request_products.quantity as presupuesto'),
					'general_requests.rating as rating',
					'general_requests.created_at as creada',
					'general_requests.project_date as project_date',
					'general_requests.deliver_date as deliver_date',
					DB::raw('CASE general_requests.status
									when 0 then "Acabo de recibir tu solicitud, en breve me comunicare contigo"
									when 1 then "En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas"
									when 2 then "Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados"
									when 3 then "Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio"
									when 4 then "Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad"
									when 5 then "Recotizar"
									when 6 then "Conforme a tu elección, ingresa tu solicitud en People Soft"
									when 7 then "Ya se envió la orden de compra al proveedor"
									when 8 then "La fecha de entrega de tu pedido es"
									when 9 then "Tu pedido llego en excelentes condiciones, en el domicilio y recibió"
									when 10 then "Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción"
									when 11 then "La encuesta ha sido contestada"
									when 12 then "Encuesta cancelada"
									END as estatus'),
					'general_requests.user_id as user_id',
					'general_requests.manager_id as manager_id',
					'general_request_products.*'
					)->withTrashed()
			->join('general_request_products','general_requests.id','=','general_request_products.id');
		
		if($active_tab == 'assigned'){
				$request->whereNotNull('manager_id')->whereNull('deleted_at');
				
		}elseif($active_tab == 'not_assigned'){
			
			$request->where('manager_id',null)->whereNull('deleted_at');

		}elseif($active_tab == 'deleted_assigned'){
			$request->whereNotNull('manager_id')->onlyTrashed();

		}elseif($active_tab == 'deleted_unassigned'){
			$request->whereNull('manager_id')->onlyTrashed();
		}

		if(Input::has('user_id')){
			$request->where('user_id',Input::get('user_id'));
		}
		
		$request->where('general_requests.created_at','>=',Input::get('since',\Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')));	
		$request->where('general_requests.created_at','<=',Input::get('until',\Carbon\Carbon::now()->format('Y-m-d')));

		return View::make('admin::general_requests.index')
			->withRequests($request->orderBy('general_requests.created_at','desc')->orderBy('rating','desc')->groupBy('solicitud')->paginate(10))
			->withActiveTab($active_tab)
			->withUsers(User::where('role', 'user_requests')->lists('gerencia','id'))
			->withManagers(User::where('role', 'manager'))
			->withInput(Input::flash());
	}


	public function show($id)
	{
		$active_tab = Input::get('active_tab','all');

		$general_request = GeneralRequest::withTrashed()->find($id);
		if($general_request){
			return View::make('admin::general_requests.show')
				->withGeneral($general_request)
				->withEstatus($this->getStatus($general_request->status))
				->withActiveTab($active_tab);   
		}else{
			return Redirect::back()->withWarning('No se encontró la orden')->withActiveTab($active_tab);
		}  
	}

	public function update($id)
	{
		
		$active_tab = Input::get('active_tab','all');	
		$general_request = GeneralRequest::withTrashed()->find($id);
		$action = Input::get('action');

		if(!$general_request)
			return Redirect::back()->withErrors("No se encontro la orden")->withActiveTab($active_tab);
		
		if(Input::has('manager_id')){
			$manager = User::find(Input::get('manager_id'));
			if(!$manager)
				return Redirect::back()->withErrors('No se encontro al asesor')->withActiveTab($active_tab);

			$general_request->manager_id = $manager->id;
			$general_request->rating = Input::get('rating');

			if($general_request->save()){
				$user = User::find($general_request->user_id);
				if($user->email){
					$email = 'jona_54_.com@ciencias.unam.mx';
					Mail::send('admin::email_templates.aviso_consultor',['general_request' => $general_request],function($message) use ($email){
					$message->to($email)->subject("Solicitud general asignada.");
					});
				}
				
				if($manager->email){
					$email = $manager->email;
					Mail::send('admin::email_templates.aviso_consultor',['general_request' => $general_request],function($message) use ($email){
					$message->to($email)->subject("Solicitud general asignada por Administrador.");
					});					
				}

				return Redirect::action('AdminGeneralRequestsController@index',['active_tab' => $active_tab])->withSuccess('Se ha asignado al asesor');
			}	
			else{
				return Redirect::back()->withErrors($general_request->getErrors())
				->withActiveTab($active_tab);
			}

		}else{

			if($general_request->restore()){
			    return Redirect::action('AdminGeneralRequestsController@index',['active_tab' => $active_tab])
				    ->withSuccess('Se restauro la orden exitosamente.');
			}else{
			  return Redirect::back()->withErrors('Ocurrio un error al restaurar la orden')
			  	->withActiveTab($active_tab);   
			}
		}
	}

	public function destroy($id)
	{
		$general_request = GeneralRequest::find($id);
		$active_tab = Input::get('active_tab','all');

		if($general_request){
			
			if($general_request->delete()){
				return Redirect::action('AdminGeneralRequestsController@index',['active_tab' => $active_tab])
					->withSuccess('Se cancelo la orden exitosamente.');
			}else{
				return Redirect::back()
					->withErrors('No se pudo cancelar  la orden')
					->withActiveTab($active_tab);
			}
			
		}else{
			return Redirect::back()
				->withErrors('No se encontro la orden')
				->withActiveTab($active_tab);
		}
	}

	private function getStatus($status){
		switch($status){
			case 0: return  'Acabo de recibir tu solicitud, en breve me comunicare contigo';
			break;
			case 1: return  'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas';
			break;
			case 2: return  'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados';
			break;
			case 3: return  'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio';
			break;
			case 4: return  'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad';
			break;
			case 5: return  'Recotizar';
			break;
			case 6: return  'Conforme a tu elección, ingresa tu solicitud en People Soft';
			break;
			case 7: return  'Ya se envió la orden de compra al proveedor';
			break;
			case 8: return  'La fecha de entrega de tu pedido es ';
			break;
			case 9: return  'Tu pedido llego en excelentes condiciones, en el domicilio y recibió';
			break;
			case 10: return  'Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción.';
			break;
			case 11: return 'La encuesta ha sido contestada';
			break;
			case 12: return 'Encuesta cancelada;';
			default: return 'Desconocido';
		}       
	}

}