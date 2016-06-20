<?

class AdminGeneralRequestsController extends AdminBaseController{

  public function index(){
  	$request = GeneralRequest::withTrashed()
                ->join('general_request_products','general_requests.id','=','general_request_products.id');
    
    $active_tab = Input::get('active_tab', 'assigned');
  
    if($active_tab == 'assigned'){
        $request->whereNotNull('manager_id');
    }elseif($active_tab == 'not_assigned'){
      $request->whereNull('manager_id');
    }elseif($active_tab == 'deleted'){
      $request->onlyTrashed();
    }


  	if(Input::has('user_id')){
  		
       $request->where('user_id',Input::get('user_id'));
  	}
  	if(Input::has('month')){
  		$request->where(DB::raw('MONTH(general_requests.created_at)'), Input::get('month'));	
  	}

  	if (Input::has('year')) {
  		$request->where(DB::raw('YEAR(general_requests.updated_at)'), Input::get('year'));
  	}

    $assigneds = ['ASIGNADO','NO ASIGNADO'];


    $active_category = ['ASIGNADO','NO ASIGNADO'];

    if(Input::has('export')){
      Excel::create('Reporte_solicitudes_asignadas',function($excel) use($request){
          $excel->sheet('Hoja_1', function($sheet) use($request) {
      

 
          $sheet->fromModel($request->select('general_requests.id as #Solicitud',
                                              'general_requests.project_title as Titulo',
                                              DB::raw('general_request_products.unit_price * general_request_products.quantity as Presupuesto'),
                                              'general_requests.rating as Criticidad',
                                              'general_requests.created_at as Fecha_de_solicitud',
                                              'general_requests.project_date as Fecha_de_inicio',
                                              'general_requests.deliver_date as Fecha_de_entrega',
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
                                                      END as Estatus')
                                              )->get());
          });
        })->download('xlsx');
    }
    return View::make('admin::general_requests.index')->withAssigneds($assigneds)->withActiveCategory($active_category)
    ->withRequests($request->orderBy('general_requests.created_at','desc')->orderBy('rating','desc')->paginate(10))->withActiveTab($active_tab)
    ->withUsers(User::where('role', 'user_requests')->lists('gerencia','id'));
  }


  public function show($id)
  {
    $general_request = GeneralRequest::find($id);
    if($general_request){
      return View::make('admin::general_requests.show')->withGeneral($general_request);   
    }else{
      return Redirect::back()->withWarning('No se encontró la orden');
    }


       
  }

  public function update($id)
  {
    $general_request = GeneralRequest::withTrashed()->find($id);
    if($general_request->restore()){
        return Redirect::action('AdminGeneralRequestsController@index')->withSuccess('Se restauro la orden exitosamente.');
    }else{
      return Redirect::back()->withErrors('Ocurrio un error al restaurar la orden');
    }
  }

  public function destroy($id)
  {
    $general_request = GeneralRequest::find($id);
    if($general_request){
      
      if($general_request->delete()){
        return Redirect::action('GeneralRequestsController@index')->withSuccess('Se cancelo la orden exitosamente.');
      }else{
        return Redirect::back()->withErrors('No se pudo cancelar  la orden');
      }
      
    }else{
        return Redirect::back()->withErrors('No se encontro la orden');
    }
  }

}