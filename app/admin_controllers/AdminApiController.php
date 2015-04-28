<?

ini_set('memory_limit', '1G');

class AdminApiController extends AdminBaseController
{

  private function convertObjectToCsv($object, $fields)
  {
    $array = [];
    foreach($fields as $field)
    {
      try{
        $array[] = $object->$field;
      }catch(Exception $e){
        Log::error($e);
        $array[] = "";
      }
    }

    return str_putcsv($array)."\n";
  }

  public function getOrdersReport()
  {
    ini_set('max_execution_time','300');
    $query = DB::table(DB::raw("(SELECT @rownum:=0) r, order_product"))->select(
      DB::raw("
      orders.created_at as FECHA_PEDIDO,
      '9999999999999990000000000' AS EIP_CTL_ID,
      1 as LOADER_REQ,
      'BPO' as SYSTEM_SOURCE,
      'PAF01' as LOADER_BU,
      @rownum:=@rownum+1 as GROUP_SEQ_NUM,
      'JC005819' as REQUESTOR_ID,
      DATE_FORMAT( NOW(), '%d/%m/%Y') as DUE_DT,
      products.sku as INV_ITEM_ID,
      products.name as DESCR254_MIXED,
      products.measure_unit as UNIT_OF_MEASURE,
      order_product.quantity as QTY_REQ,
      0 as PRICE_REQ,
      'MXN' as CURRENCY_CD,
      '' as VENDOR_ID,
      users.ccosto as LOCATION,
      '' as CATEGORY_ID,
      users.ccosto as SHIPTO_ID,
      '' as REQ_ID,
      5207030800 as ACCOUNT,
      5405002201 as ALTACCT,
      users.ccosto as DEPTID,
      'RCV' as PRODUCT,
      '' as CC1,
      '' as PROJECT_ID,
      '' as ANALYSIS_TYPE,
      'PAF01' as BUSINESS_UNIT_GL,
      @rownum as LINE_NBR,
      'Y' as CALC_PRICE_FLG,
      '' as CAP_NUM,
      '' as SHIP_TO_CUST_ID,
      'JC005819' as INTROD,
      categories.name as CATEGORY
      ")
    )->join('products', 'products.id', '=', 'order_product.product_id')
      ->join('orders', 'orders.id' , '=', 'order_product.order_id')
      ->leftJoin('users', 'users.id', '=', 'orders.user_id')
      ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
      ->orderBy('orders.id')
      ->where(DB::raw('MONTH(orders.created_at)'), Input::get('month'))
      ->where(DB::raw('YEAR(orders.updated_at)'), Input::get('year'))
      ->where('categories.name', Input::get('category'));

    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    if(Request::ajax()){
      $items = $query->get();
      return Response::json([
        'status' => 200,
        'orders' => $items,
        'headers' => $headers
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";

  Log::info($data);

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
    Excel::create('Reporte_Papeleria_'.$mes.'_'.$año , function($excel) use($result){
     $excel->sheet('segunda hoja',function($sheet)use($result){
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


  }

  public function getBcOrdersReport()
  {
    ini_set('max_execution_time', '300');
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

      '' AS PUESTO_ATRACCION_GERENTE
    ")->join('business_cards', 'business_cards.id', '=', 'bc_order_business_card.business_card_id')
    ->join('bc_orders', 'bc_orders.id', '=', 'bc_order_business_card.bc_order_id')
    ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
    ->where(DB::raw('MONTH(bc_orders.created_at)'), Input::get('month'))
    ->where(DB::raw('YEAR(bc_orders.updated_at)'), Input::get('year'));

    $query2 = DB::table('blank_cards_bc_order')->selectRaw("
      bc_orders.created_at as FECHA_PEDIDO,
      bc_orders.id as NUM_PEDIDO,
      blank_cards_bc_order.quantity as CANTIDAD,
      users.gerencia as GERENCIA,
      DATE_FORMAT(bc_orders.updated_at, '%d/%m/%Y') AS FECHA,
      'Tarjetas blancas' AS NOMBRE,
      blank_cards_bc_order.nombre_puesto AS NOMBRE_PUESTO,
      '' AS EMAIL,
      '' AS TELEFONO,
      '' AS CELULAR,
      '' AS WEB,
      users.ccosto AS CENTRO_COSTO,
      '' AS DIRECCION,
      '' AS DIRECCION_ALTERNATIVA,
      '' AS PUESTO_ATRACCION_GERENTE
    ")->join('bc_orders', 'bc_orders.id', '=', 'blank_cards_bc_order.bc_order_id')
    ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
    ->where(DB::raw('MONTH(bc_orders.created_at)'), Input::get('month'))
    ->where(DB::raw('YEAR(bc_orders.updated_at)'), Input::get('year'));

    $query3 = DB::table('bc_orders_extras')->selectRaw("
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
      'Atracción de talento' AS PUESTO_ATRACCION_GERENTE
    ")->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id')
    ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
    ->where('bc_orders_extras.talento_nombre', '!=', "''")->whereNotNull('bc_orders_extras.talento_nombre')
    ->where(DB::raw('MONTH(bc_orders.created_at)'), Input::get('month'))
    ->where(DB::raw('YEAR(bc_orders.updated_at)'), Input::get('year'));


    $query4 = DB::table('bc_orders_extras')->selectRaw("
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
      'Gerente comercial' AS PUESTO_ATRACCION_GERENTE
    ")->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id')
    ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
    ->where('bc_orders_extras.gerente_nombre', '!=', "''")->whereNotNull('bc_orders_extras.gerente_nombre')
    ->where(DB::raw('MONTH(bc_orders.created_at)'), Input::get('month'))
    ->where(DB::raw('YEAR(bc_orders.updated_at)'), Input::get('year'));


    switch(Input::get('type')){
      case 1:
        break;
      case 2:
        $query = $query2;
        break;
      case 3:
        $query = $query3;
        break;
      case 4:
        $query = $query4;
        break;
    }

    $q = clone $query;
    $item = $q->first();

    $headers = $item ?  array_keys(get_object_vars( $item )) : [];

    if(Request::ajax()){
      $items = $query->get();
      return Response::json([
        'status' => 200,
        'orders' => $items,
        'headers' => $headers
        ]);
    }else{

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
        Excel::create('Reporte_Tarjetas_'.$mes.'_'.$año, function($excel) use($result){
         $excel->sheet('segunda hoja',function($sheet)use($result){
           $sheet->fromArray($result);
            });
          })->download('xls');
      }


      $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"reporte_pedidos_tp_{$datetime}.csv\"",
      );
      return Response::make($data, 200, $headers);
    }
  }

  public function getUsersReport()
  {
    $users = User::orderBy('role')->get();
    $result = [];
    foreach($users as $user){
      $perfil = NULL;
      switch($user->role){
        case 'admin':
          $perfil = 'Administrador';
          break;
        case 'manager':
          $perfil = 'Asesor';
          break;
        case 'user_requests';
          $perfil = 'Proyectos';
          break;
        case 'user_paper';
          $perfil = 'Papelería';
          break;
      }
      $result[] = [
        'CENTRO_COSTOS' => $user->ccosto,
        'GERENCIA/NOMBRE' => $user->gerencia,
        'LINEA_DE_NEGOCIO' => $user->linea_negocio,
        'PERFIL' => $perfil
      ];

    }

    $datetime = \Carbon\Carbon::now()->format('YmdHi');
    Excel::create('Reporte_usuarios_'.$datetime, function($excel) use($result){
      $excel->sheet('Usuarios',function($sheet)use($result){
        $sheet->fromArray($result);
      });
    })->download('xls');
  }

}
