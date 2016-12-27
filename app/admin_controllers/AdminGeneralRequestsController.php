<?
use \Carbon\Carbon;
class AdminGeneralRequestsController extends AdminBaseController{

	public function index($active_tab = null){
		
		if(!$active_tab)
			$active_tab = Input::get('active_tab', 'all');

		$request = GeneralRequest::query()->withTrashed();
		
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

		if(Input::has('manager_id')){
			$request->where('manager_id',Input::get('manager_id'));
		}

		if(Input::has('id')){
			$request->where('id',Input::get('id'));
		}

		if(Input::has('status')){
			$request->where('status',Input::get('status'));
		}


		$since = Input::get('since',\Carbon\Carbon::now()->subMonths(1)->format('d-m-Y'));
		$until = Input::get('until',\Carbon\Carbon::now()->format('d-m-Y'));


		$request->where('general_requests.created_at','>=',\Carbon\Carbon::createFromFormat('d-m-Y',$since)->startOfDay()->format('Y-m-d'))
			->where('general_requests.created_at','<=',\Carbon\Carbon::createFromFormat('d-m-Y',$until)->addDay()->format('Y-m-d'))
			->orderBy('general_requests.created_at','desc')
			->orderBy('rating','desc');

		if(Input::has('excel')){
			$headers = 
				[
					'NÚMERO DE SOLICITUD',
					'TIPO DE PROYECTO',
					'TÍTULO DE PROYECTO',
					'ESTATUS',
					'PRESUPUESTO',
					'FECHA DE SOLICITUD',
					'FECHA DE INICIO',
					'FECHA DE ENTREGA',
					'LÍNEA DE NEGOCIO',
					'LISTA DE DISTRIBUCIÓN',
					'ASESOR',
					'USUARIO DE PROYECTOS',
					'COMENTARIOS',
					'ORDER_PEOPLE',
					'TOTAL DE PRODUCTOS',
					'USUARIOS FINALES',
					'NOMBRE DE EMPLEADO',
					'NÚMERO DE EMPLEADO',
					'EMAIL DE EMPLEADO',
					'EXTENSIÓN DE EMPLEADO',
					'CELULAR DE EMPLEADO',
					
				];

			$datetime = \Carbon\Carbon::now()->format('d-m-Y');
			
			
			Excel::create('Reporte_solicitudes_generales_'.$datetime, function($excel) use($request,$headers){
			  $excel->sheet('Solicitudes',function($sheet)use($request,$headers){
				$sheet->appendRow($headers);
				$general_requests = $request->get(); 
				foreach ($general_requests as $request) {
					$query = DB::table('general_request_products')->select(DB::raw('quantity * unit_price as presupuesto'))->where('general_request_id',$request->id)->groupBy('general_request_id');

					$presupuesto = $query->select(DB::raw('quantity * unit_price as presupuesto'))->first();
					$total_products = $query->select(DB::raw('sum(quantity) as total_products'))->first();

					if($presupuesto)
						$presupuesto = $presupuesto->presupuesto;
					else 
						$presupuesto = 0;

					if ($total_products) 
						$total_products = $total_products->total_products;
					else
						$total_products = 0;

					if($request->kind == 0)
						$kind = "PRODUCTO";
					else
						$kind = "SERVICIO";

					if($request->distribution_list == 0)
						$distribution_list = 'NO';
					elseif ($request->distribution_list == 1)
						$distribution_list = 'SI';
					else
						$distribution_list = 'PENDIENTE';


					$sheet->appendRow([
						$request->id,
						$kind,
						$request->project_title,
						$this->getStatus($request->status) ,
						$presupuesto,
						$request->created_at ? $request->created_at->format('d-m-Y') : 'SIN FECHA',
						$request->project_date ? $request->project_date->format('d-m-Y') : 'SIN FECHA',
						$request->deliver_date ? $request->deliver_date->format('d-m-Y') : 'SIN FECHA',
						$request->linea_negocio,
						$distribution_list,
						$request->manager ? $request->manager->gerencia : 'SIN ASESOR',
						$request->user ? $request->user->gerencia : 'SIN USUARIO',
						$request->comments,
						$request->num_order_people,
						$total_products,
						$request->project_dest,
						$request->employee_name,
						$request->employee_number,
						$request->employee_email,
						$request->employee_ext,
						$request->employee_cellphone
					]);	
				}
			  });
			})->download('xlsx');	
		}
		

		return View::make('admin::general_requests.index')
			->withRequests($request->paginate(10))
			->withActiveTab($active_tab)
			->withUsers(User::where('role', 'user_requests')->orderBy('gerencia')->lists('gerencia','id'))
			->withManagers(User::where('role', 'manager'))
			->withSince($since)
			->withUntil($until)
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
					$email = $user->email;
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