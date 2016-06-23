<?
use \Carbon\Carbon;
class AdminGeneralRequestsController extends AdminBaseController{

  public function index(){
    $active_tab = Input::get('active_tab', 'assigned');
  	$request = GeneralRequest::select('general_requests.id as solicitud',
                                              'general_requests.project_title as titulo',
                                              DB::raw('general_request_products.unit_price * general_request_products.quantity as presupuesto'),
                                              'general_requests.rating as rating',
                                              'general_requests.created_at as created_at',
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
                                                      END as estatus')
                                              )->withTrashed()
                ->join('general_request_products','general_requests.id','=','general_request_products.id');
    
    $request_excel = GeneralRequest::query();
    
    if($active_tab == 'assigned'){
        $request->whereNotNull('manager_id');
        $request_excel->whereNotNull('manager_id');
    }elseif($active_tab == 'not_assigned'){
      
      $request->where('manager_id',null);
      $request_excel->where('manager_id',null);

    }elseif($active_tab == 'deleted_assigned'){
      $request->whereNotNull('manager_id')->onlyTrashed();
      $request_excel->whereNotNull('manager_id')->onlyTrashed();

    }elseif($active_tab == 'deleted_unassigned'){
      $request->whereNull('manager_id')->onlyTrashed();
      $request_excel->whereNull('manager_id')->onlyTrashed();
    }


  	if(Input::has('user_id')){
       $request->where('user_id',Input::get('user_id'));
       $request_excel->where('user_id',Input::get('user_id'));
  	}

    if(Input::has('since')){
      $request->where('general_requests.created_at','>=',Input::get('since'));
      $request_excel->where('user_id',Input::get('user_id'));
    }

    if(Input::has('until')){
      $request->where('general_requests.created_at','<=',DateTime::createFromFormat('Y-m-d',Input::get('until')));
      $request_excel->where('user_id',Input::get('user_id'));
    }

    $assigneds = ['ASIGNADO','NO ASIGNADO'];


    $active_category = ['ASIGNADO','NO ASIGNADO'];
    foreach ($request_excel->get() as $item) {
        
        $itemArray = [];
        $itemArray['NUMERO DE SOLICITUD GENERAL']   = $item->id;
        $itemArray['TITULO DE PROYECTO']   = $item->project_title;

        $itemArray['CONSULTOR'] =  $item->manager ? $item->manager->nombre : 'SIN CONSULTOR';
        
        $itemArray['USUARIO_PROYECTOS'] = $item->user ? $item->user->nombre : 'USUARIO PROYECTOS';
        
        $result[] = $itemArray;
    }
        

    if(Input::has('export')){
      Excel::create('Reporte_solicitudes_asignadas',function($excel) use($result){
          $excel->sheet('Hoja_1', function($sheet) use($result) {
                $sheet->fromArray($result);
          });
        })->download('xls');
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