<?php

class AdminReportsController extends AdminBaseController{

  

  public function getIndex()
  {
  $categories = Category::lists('name','id');
  
  return View::make('admin::reports.index')->withCategories($categories);
  }
  
  public function getOrdersReport()
  {
  $categories = Category::lists('name','id');
  $management = User::where('role','!=','admin')->lists('gerencia','id');
  $business_line = User::distinct()->where('role','!=','admin')->lists('linea_negocio','linea_negocio');
  
  return View::make('admin::reports.orders')->withCategories($categories)->withGerencia($management)->withBusinessLine($business_line);
  }
  private function sumDay($date){
    return \Carbon\Carbon::createFromFormat('Y-m-d',$date)->addDay()->format('Y-m-d');
  } 

    public function getBcOrdersReport()
    {
    $management = User::where('role','!=','admin')->lists('gerencia');
    $regions = Region::lists('name','id'); 
    $divisionals = Divisional::lists('name','id');
    $active_tab = Input::get('active_tab','tarjetas_presentacion');
    $since = Input::get('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d'));
    $until = Input::get('until',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d'));

      switch ($active_tab) {
      case 'tarjetas_presentacion':
        $query = DB::table('bc_order_business_card')->selectRaw("
          bc_orders.created_at as FECHA_PEDIDO,
          bc_orders.id AS NUM_PEDIDO,
          100 AS CANTIDAD,
          users.gerencia AS GERENCIA,
          DATE_FORMAT(bc_orders.updated_at, '%d/%m/%Y') AS FECHA,
          business_cards.nombre AS NOMBRE,
          business_cards.nombre_puesto AS NOMBRE_PUESTO,
          business_cards.email AS EMAIL,
          business_cards.telefono AS TELEFONO,
          business_cards.celular AS CELULAR,
          business_cards.web AS WEB,
          business_cards.ccosto AS CENTRO_COSTO,
          business_cards.direccion AS DIRECCION,
          business_cards.direccion_alternativa AS DIRECCION_ALTERNATIVA,
          CASE bc_orders.status
          WHEN  '0' THEN  'PENDIENTE'
          WHEN  1 THEN  'RECIBIDO'
          WHEN  2 THEN  'RECIBIDO_INCOMPLETO'
          END AS ESTATUS "
        )->join('business_cards', 'business_cards.id', '=', 'bc_order_business_card.business_card_id')
        ->join('bc_orders', 'bc_orders.id', '=', 'bc_order_business_card.bc_order_id');

      break;
        case 'tarjetas_blancas':
          $query = DB::table('blank_cards_bc_order')->selectRaw("
          bc_orders.created_at as FECHA_PEDIDO,
          bc_orders.id as NUM_PEDIDO,
          blank_cards_bc_order.quantity as CANTIDAD,
          users.gerencia as GERENCIA,
          DATE_FORMAT(bc_orders.updated_at, '%d/%m/%Y') AS FECHA,
          'Tarjetas blancas' AS NOMBRE,
          blank_cards_bc_order.nombre_puesto AS NOMBRE_PUESTO,
          blank_cards_bc_order.email AS EMAIL,
          blank_cards_bc_order.telefono_tarjetas AS TELEFONO,
          '' AS CELULAR,
          '' AS WEB,
          users.ccosto AS CENTRO_COSTO,
          '' AS DIRECCION,
          blank_cards_bc_order.direccion_alternativa_tarjetas AS DIRECCION_ALTERNATIVA,
          blank_cards_bc_order.email as EMAIL_TARJETAS,
          CASE bc_orders.status
          WHEN  '0' THEN  'PENDIENTE'
          WHEN  1 THEN  'RECIBIDO'
          WHEN  2 THEN  'RECIBIDO_INCOMPLETO'
          END AS ESTATUS")
          ->whereNull('bc_orders.deleted_at')
          ->join('bc_orders', 'bc_orders.id', '=', 'blank_cards_bc_order.bc_order_id');
      break;
        case 'atraccion_talento':
          $query = DB::table('bc_orders_extras')->selectRaw("
          bc_orders.created_at as FECHA_PEDIDO,
          bc_orders.id as NUM_PEDIDO,
          100 as CANTIDAD,
          users.gerencia as GERENCIA,
          DATE_FORMAT(bc_orders.updated_at, '%d/%m/%Y') AS FECHA,
          bc_orders_extras.talento_nombre AS NOMBRE,
          '' AS NOMBRE_PUESTO,
          talento_email AS EMAIL,
          talento_tel AS TELEFONO,
          talento_cel AS CELULAR,
          '' AS WEB,
          users.ccosto AS CENTRO_COSTO,
          bc_orders_extras.talento_direccion AS DIRECCION,
          '' AS DIRECCION_ALTERNATIVA,
          'Atracción de talento' AS PUESTO_ATRACCION_GERENTE,
          CASE bc_orders.status
          WHEN  '0' THEN  'PENDIENTE'
          WHEN  1 THEN  'RECIBIDO'
          WHEN  2 THEN  'RECIBIDO_INCOMPLETO'
          END AS ESTATUS")
          ->where('bc_orders_extras.talento_nombre', '!=', "''")
          ->whereNotNull('bc_orders_extras.talento_nombre')
          ->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id');
      break;
      case 'gerente_comercial':
          $query = DB::table('bc_orders_extras')->selectRaw("
          bc_orders.created_at as FECHA_PEDIDO,
          bc_orders.id as NUM_PEDIDO,
          100 as CANTIDAD,
          users.gerencia as GERENCIA,
          DATE_FORMAT(bc_orders.updated_at, '%d/%m/%Y') AS FECHA,
          bc_orders_extras.gerente_nombre AS NOMBRE,
          '' AS NOMBRE_PUESTO,
          gerente_email AS EMAIL,
          gerente_tel AS TELEFONO,
          gerente_cel AS CELULAR,
          '' AS WEB,
          users.ccosto AS CENTRO_COSTO,
          bc_orders_extras.gerente_direccion AS DIRECCION,
          '' AS DIRECCION_ALTERNATIVA,
          'Gerente comercial' AS PUESTO_ATRACCION_GERENTE,
          CASE bc_orders.status
          WHEN  '0' THEN  'PENDIENTE'
          WHEN  1 THEN  'RECIBIDO'
          WHEN  2 THEN  'RECIBIDO_INCOMPLETO'
          END AS ESTATUS")
          ->where('bc_orders_extras.gerente_nombre', '!=', "''")
          ->whereNotNull('bc_orders_extras.gerente_nombre')
          ->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id');
      break; 
      default:
          return Redirect::back()->withErrors('Algo salio mal, intente mas tarde.');
      break;
    }

    $query = $query->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
      ->whereNull('bc_orders.deleted_at')
      ->where('bc_orders.created_at','>=',$since)
      ->where('bc_orders.created_at','<=',$this->sumDay($until))
      ->orderBy('bc_orders.created_at');

    if(Input::has('divisional_id')){
      $query->where('users.divisional_id',Input::get('divisional_id'));
    }

    if (Input::has('num_pedido')){
      $query->where('bc_orders.id','like','%'.Input::get('num_pedido').'%');
    }

    if(Input::has('region_id')){
      $query->where('users.region_id',Input::get('region_id'));
    }
    dd($query->get());
    Log::debug($query->get());


    if(Input::has('excel')){

        $headers = [   
                    "FECHA_PEDIDO",
                    "NUM_PEDIDO",
                    "CANTIDAD",
                    "GERENCIA",
                    "FECHA",
                    "NOMBRE_PUESTO",
                    "EMAIL",
                    "TELEFONO",
                    "CELULAR",
                    "WEB",
                    "DIRECCIÓN",
                    "DIRECCIÓN_ALTERNATIVA",
                    "ESTATUS"
                ];
      if($active_tab == 'atraccion_talento' || $active_tab == 'gerente_comercial')
        $headers[] = "PUESTO_ATRACCION_GERENTE";

      $datetime = \Carbon\Carbon::now()->format('d-m-Y');

      Excel::create('Reporte_pedidos_tarjetas_'.$datetime, function($excel) use($query,$headers,$active_tab){
        $excel->sheet('Solicitudes',function($sheet)use($query,$headers,$active_tab){

        $sheet->appendRow($headers);
        foreach ($query->get() as $bc_order) {

          if($active_tab == 'atraccion_talento' || $active_tab == "gerente_comercial"){
            $sheet->appendRow([
                          $bc_order->FECHA_PEDIDO,
                          $bc_order->NUM_PEDIDO,
                          $bc_order->CANTIDAD,
                          $bc_order->GERENCIA,
                          $bc_order->FECHA,
                          $bc_order->NOMBRE_PUESTO,
                          $bc_order->EMAIL,
                          $bc_order->TELEFONO,
                          $bc_order->CELULAR,
                          $bc_order->WEB,
                          $bc_order->DIRECCION,
                          $bc_order->DIRECCION_ALTERNATIVA,
                          $bc_order->ESTATUS,
                          $bc_order->PUESTO_ATRACCION_GERENTE
            ]); 
          }else{
            $sheet->appendRow([
                          $bc_order->FECHA_PEDIDO,
                          $bc_order->NUM_PEDIDO,
                          $bc_order->CANTIDAD,
                          $bc_order->GERENCIA,
                          $bc_order->FECHA,
                          $bc_order->NOMBRE_PUESTO,
                          $bc_order->EMAIL,
                          $bc_order->TELEFONO,
                          $bc_order->CELULAR,
                          $bc_order->WEB,
                          $bc_order->DIRECCION,
                          $bc_order->DIRECCION_ALTERNATIVA,
                          $bc_order->ESTATUS
            ]);
          }   
        }
        });
      })->download('xlsx');
    }else{

      return View::make('admin::reports.bc_orders')
        ->withManagement($management)
        ->withRegions($regions)
        ->withDivisionals($divisionals)
        ->withActiveTab($active_tab)
        ->withBcOrders($query->paginate(20))
        ->withInput(Input::flash());
      
      } 
    } 

  public function getUserOrdersReport()
  {
  return View::make('admin::reports.user_orders');
  }

  public function getProductOrdersReport()
  { 
  $categories = Category::lists('name','id');
  return View::make('admin::reports.product_orders')->withCategories($categories);
  }

  function  getActiveUserOrdersReportQuery()
  {
  $query = DB::table('users')
  ->select('users.id','users.ccosto','gerencia','linea_negocio','role',
    DB::raw('sum(order_product.quantity) as quantity'))
  ->join('orders','users.id','=','orders.user_id')
  ->join('order_product','orders.id','= ','order_product.order_id')
  ->groupBy('users.id')
  ->orderBy('quantity', 'DESC');

  return $query;
  }

  function getActiveUserOrdersReportExcel()
  { 
     ini_set('max_execution_time','300');
    $query = AdminReportsController::getActiveUserOrdersReportQuery();
   
    
    $query->where(DB::raw('MONTH(orders.created_at)'), Input::get('month'))->where(DB::raw('YEAR(orders.created_at)'), Input::get('year'));
    
    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    $datetime = \Carbon\Carbon::now()->format('YmdHi');
    $data = str_putcsv($headers)."\n";
    $result = [$headers];
    foreach($query->get() as $item){
    $itemArray = [];
    foreach($headers as $header){
    $itemArray[] = $item->{$header};
    }
    $result[] = $itemArray;
    }
    if($result){
     $mes = Input::get('month');
     $año = Input::get('year');
    Excel::create('Reporte_Usuarios_Inactivos_'.$mes.'_'.$año , function($excel) use($result){
     $excel->sheet('hoja 1',function($sheet)use($result){
       $sheet->fromArray($result);
      });
      })->download('xls');
    }

    $headers = array(
    'Content-Type' => 'text/csv',
    'Content-Disposition' => "attachment; filename=\"reporte_pedidos_{$datetime}.csv\"",
    );
    return Response::make($data, 200, $headers);
  }

   public function getActiveUserOrdersChangeReport()
  {
  ini_set('max_execution_time','300');

  $query = AdminReportsController::getActiveUserOrdersReportQuery();
  $query->where(DB::raw('MONTH(orders.created_at)'), \Carbon\Carbon::now('America/Mexico_City')->month)->where(DB::raw('YEAR(orders.created_at)'), \Carbon\Carbon::now('America/Mexico_City')->year);

  return View::make('admin::reports.active_user_orders')->withOrders($query->paginate(10));
  }


   public function getActiveUserOrdersReport()
  {
  ini_set('max_execution_time','300');

  $query = AdminReportsController::getActiveUserOrdersReportQuery();
  $query->where(DB::raw('MONTH(orders.created_at)'), Input::get('month'))->where(DB::raw('YEAR(orders.created_at)'), Input::get('year'));

  return View::make('admin::reports.active_user_orders')->withOrders($query->paginate(10));
  }

  public function getTotalUserOrdersReport(){
  return View::make('admin::reports.total_user_orders');
  }

  public function getGeneralRequestReport()
  {
  
  return View::make('admin::reports.general_request');
  }

  /**
  *Este metodo nos lleva a la vista donde estaran todas las ordenes de productos, muebles y tarjetas y tendra filtro para empleado
  *@return regresa la vista donde encontraremos el reporte.
  */
  public function getAllProductsReport()
  {
  $categories = Category::all()->lists('name','id');
  $products = Product::all()->lists('name','id');

  return View::make('admin::reports.all_products')->withCategories($categories)->withProducts($products);;
  }
  public function getFurnituresOrdersReport()
  {
  $categories = Category::lists('name','id');
  $management = User::where('role','!=','admin')->lists('gerencia','id');
  $business_line = User::distinct()->where('role','!=','admin')->lists('linea_negocio','linea_negocio');
  $divisionals = Divisional::lists('name','id');
  return View::make('admin::reports.furnitures')
    ->withCategories($categories)
    ->withGerencia($management)
    ->withBusinessLine($business_line)
    ->withDivisionals($divisionals);
  }

  public function getBIReport(){
  $users = User::all();
  $categories = Category::all()->lists('name','id');
  $products = Product::all()->lists('name','id');

  return View::make('admin::reports.bi')
  ->withUsers($users)
  ->withCategories($categories)
  ->withProducts($products);

  }
  /**
  *Esta función la usamos para crear el PDF en la seccion de ReportesBI y Reportes Tarjetas
  */
  public function postCreatePdf()
  {

  $pdf = App::make('dompdf');
  $pdf->loadHTML(Input::get('htmlContent'));
  
  return $pdf->download('Reporte.pdf');
  }
  
  public function getMacOrdersReport()
  {
  $categories = MacCategory::lists('name','id');
  $products = MacProduct::lists('name','id');

  return View::make('admin::reports.mac_orders')->withCategories($categories)->withProducts($products);
  }

  
  public function getCorporationOrdersReport()
  {
  $categories = CorporationCategory::lists('name','id');
  $products = CorporationProduct::lists('name','id');

  return View::make('admin::reports.corporative_orders')->withCategories($categories)->withProducts($products);
  }


  public function getBiMacReport()
  {
  $users = User::all();
  $categories = MacCategory::all()->lists('name','id');
  $products = MacProduct::all()->lists('name','id');

  return View::make('admin::reports.mac_bi')
  ->withUsers($users)
  ->withCategories($categories)
  ->withProducts($products);
  }

  public function getBiCorporationReport()
  {
  $users = User::all();
  $categories = CorporationCategory::all()->lists('name','id');
  $products = CorporationProduct::all()->lists('name','id');

  return View::make('admin::reports.corporation_bi')
  ->withUsers($users)
  ->withCategories($categories)
  ->withProducts($products);
  }

}
?>
