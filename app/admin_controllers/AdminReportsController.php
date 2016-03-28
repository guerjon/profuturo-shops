<?

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

  public function getBcOrdersReport()
  {
    $management = User::where('role','!=','admin')->lists('gerencia');
    $regions = Region::lists('name','id'); 
    $divisionals = Divisional::lists('name','id');
    return View::make('admin::reports.bc_orders')->withManagement($management)->withRegions($regions)->withDivisionals($divisionals);
  } 

  public function getUserOrdersReport()
  {
    return View::make('admin::reports.user_orders');
  }

  public function getProductOrdersReport()
  { $categories = Category::lists('name','id');
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

}
