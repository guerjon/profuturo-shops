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
    
      $query = DB::table(DB::raw("(SELECT @rownum:=0) r, order_product"))->select(DB::raw("
      orders.created_at as FECHA_PEDIDO,
      '9999999999999990000000000' AS EIP_CTL_ID,
      1 as LOADER_REQ,
      'BPO' as SYSTEM_SOURCE,
      'PAF01' as LOADER_BU,
      @rownum:=@rownum+1 as GROUP_SEQ_NUM,
      'KA003035' as REQUESTOR_ID,
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
      users.linea_negocio as LINEA_NEGOCIO,
      users.ccosto as CCOSTO,
      @rownum as LINE_NBR,
      'Y' as CALC_PRICE_FLG,
      '' as CAP_NUM,
      '' as SHIP_TO_CUST_ID,
      'KA003035' as INTROD,
      categories.name as CATEGORY,
      products.id_people as ID_PEOPLE,
      (products.price * order_product.quantity) as PRICE,
      orders.id as ORDER_ID,
      address.domicilio as ADDRESS,
      products.mba_code as MBA
      "))
      ->join('products', 'products.id', '=', 'order_product.product_id')
      ->join('orders', 'orders.id' , '=', 'order_product.order_id')
      ->leftJoin('users', 'users.id', '=', 'orders.user_id')
      ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
      ->leftJoin('address','address.id','=','users.address_id')
      ->orderBy('orders.id')
      ->whereNull('orders.deleted_at');

    if(Input::has('gerencia')){
      $query->where('users.id','=',Input::get('gerencia'));
    }
    
    if(Input::has('linea_negocio')){
      $query->where('users.linea_negocio','=',Input::get('linea_negocio'));
    }
    if(Input::has('category_id')){
      $category = Input::get('category_id') + 1;
      $query->where('categories.id','=',$category); 
    }
    if(Input::has('divisional_id')){
      $query->where('users.divisional_id','=',Input::get('divisional_id')); 
    }

    if(Input::has('since')){
      $query->where('orders.created_at','>=',Input::get('since'));
    }

    if(Input::has('until')){
      $query->where('orders.created_at','<=',Input::get('until'));
    }

    if(Input::has('since'))
      $query->where('orders.created_at','>=',Input::get('since'));
    if(Input::has('until'))
      $query->where('orders.created_at','<=',Input::get('until'));

    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    foreach ($q as $qs) {
      $q->tonteria = 1;
    }
    

    if(Request::ajax()){
      
      return Response::json([
        'status' => 200,
        'orders_full' => $query->paginate(10)->toJson(),
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
         
          Excel::create('reporte_papeleria', function($excel) use($result){
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
  }



  public function getMacOrders()
  {
    ini_set('max_execution_time','300');
    
      $query = DB::table(DB::raw("(SELECT @rownum:=0) r, mac_order_mac_product"))->select(DB::raw("
      mac_orders.created_at as FECHA_PEDIDO,
      '9999999999999990000000000' AS EIP_CTL_ID,
      1 as LOADER_REQ,
      'BPO' as SYSTEM_SOURCE,
      'PAF01' as LOADER_BU,
      @rownum:=@rownum+1 as GROUP_SEQ_NUM,
      'KA003035' as REQUESTOR_ID,
      DATE_FORMAT( NOW(), '%d/%m/%Y') as DUE_DT,
      mac_products.name as DESCR254_MIXED,
      mac_products.measure_unit as UNIT_OF_MEASURE,
      mac_order_mac_product.quantity as QTY_REQ,
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
      users.linea_negocio as LINEA_NEGOCIO,
      users.ccosto as CCOSTO,
      @rownum as LINE_NBR,
      'Y' as CALC_PRICE_FLG,
      '' as CAP_NUM,
      '' as SHIP_TO_CUST_ID,
      'KA003035' as INTROD,
      mac_categories.name as CATEGORY,
      (mac_products.price * mac_order_mac_product.quantity) as PRICE,
      address.domicilio as ADDRESS,
      mac_orders.id as ORDER_ID,
      mac_products.mba_code as MBA
      "))
      ->join('mac_products', 'mac_products.id', '=', 'mac_order_mac_product.mac_product_id')
      ->join('mac_orders', 'mac_orders.id' , '=', 'mac_order_mac_product.mac_order_id')
      ->leftJoin('users', 'users.id', '=', 'mac_orders.user_id')
      ->leftJoin('mac_categories', 'mac_products.mac_category_id', '=', 'mac_categories.id')
      ->leftJoin('address','address.id','=','users.address_id')
      ->orderBy('mac_orders.id')->whereNull('mac_orders.deleted_at');




    if(Input::has('status'))
      $query->where('mac_orders.status','=',Input::get('status'));

    if(Input::has('category_id'))
      $query->where('mac_categories.id','=',Input::get('category_id')); 

    if(Input::has('divisional_id'))
      $query->where('users.divisional_id','=',Input::get('divisional_id')); 

    if(Input::has('ccosto'))
      $query->where('users.ccosto','=',Input::get('ccosto')); 

    if(Input::has('order_id'))
      $query->where('mac_orders.id',Input::get('order_id'));

    if(Input::has('since'))
      $query->where('mac_orders.created_at','>=',Input::get('since'));
    if(Input::has('until'))
      $query->where('mac_orders.created_at','<=',Input::get('until'));


    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    if(Request::ajax()){
      $items = $query->paginate(10)->toJson();
      return Response::json([
        'status' => 200,
        'orders_full' => $items,
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
          Excel::create('reporte_mac'.$mes.'_'.$año , function($excel) use($result){
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
  }

  public function getCorporationOrders()
  {
    ini_set('max_execution_time','300');
    
      $query = DB::table(DB::raw("(SELECT @rownum:=0) r, corporation_order_corporation_product"))->select(DB::raw("
      corporation_orders.created_at as FECHA_PEDIDO,
      '9999999999999990000000000' AS EIP_CTL_ID,
      1 as LOADER_REQ,
      'BPO' as SYSTEM_SOURCE,
      'PAF01' as LOADER_BU,
      @rownum:=@rownum+1 as GROUP_SEQ_NUM,
      'KA003035' as REQUESTOR_ID,
      DATE_FORMAT( NOW(), '%d/%m/%Y') as DUE_DT,
      corporation_products.name as DESCR254_MIXED,
      corporation_products.measure_unit as UNIT_OF_MEASURE,
      corporation_order_corporation_product.quantity as QTY_REQ,
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
      users.linea_negocio as LINEA_NEGOCIO,
      users.ccosto as CCOSTO,
      @rownum as LINE_NBR,
      'Y' as CALC_PRICE_FLG,
      '' as CAP_NUM,
      '' as SHIP_TO_CUST_ID,
      'KA003035' as INTROD,
      corporation_categories.name as CATEGORY,
      (corporation_products.price * corporation_order_corporation_product.quantity) as PRICE,
      address.domicilio as ADDRESS
      "))
      ->join('corporation_products', 'corporation_products.id', '=', 'corporation_order_corporation_product.corp_product_id')
      ->join('corporation_orders', 'corporation_orders.id' , '=', 'corporation_order_corporation_product.corp_order_id')
      ->leftJoin('users', 'users.id', '=', 'corporation_orders.user_id')
      ->leftJoin('corporation_categories', 'corporation_products.corporation_category_id', '=', 'corporation_categories.id')
      ->leftJoin('address','address.id','=','users.address_id')
      ->orderBy('corporation_orders.id')->whereNull('corporation_orders.deleted_at');


    if(Input::has('month_init') && Input::has('month_end')){
      $query->where(DB::raw('MONTH(corporation_orders.created_at)'),'>=',Input::get('month_init'))
            ->where(DB::raw('MONTH(corporation_orders.created_at)'),'<=',Input::get('month_end'));
    }
    if(Input::has('year'))
      $query->where(DB::raw('YEAR(corporation_orders.updated_at)'), Input::get('year'));


    if(Input::has('status'))
      $query->where('corporation_orders.status','=',Input::get('status'));

    if(Input::has('category_id'))
      $query->where('corporation_categories.id','=',Input::get('category_id')); 

    if(Input::has('divisional_id'))
      $query->where('users.divisional_id','=',Input::get('divisional_id')); 

    if(Input::has('ccosto'))
      $query->where('users.ccosto','=',Input::get('ccosto')); 

    if(Input::has('order_id'))
      $query->where('corporation_orders.id',Input::get('order_id'));

    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    if(Request::ajax()){
      $items = $query->paginate(10)->toJson();
      return Response::json([
        'status' => 200,
        'orders_full' => $items,
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
          Excel::create('reporte_mac'.$mes.'_'.$año , function($excel) use($result){
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
  }
  /**
  *Metodo auxiliar para el metodo getBcOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function bcOrdersStatus($report){
    $orders = clone $report;

    $orders = $orders->select(DB::raw('count(bc_orders.status) as STATUS'))
                     ->groupBy('bc_orders.status')
                     ->get();

    $orders_deliver_pending = [];

    foreach ($orders as $order) 
    {
     $orders_deliver_pending[] = $order->STATUS;
    }                                       
    return $orders_deliver_pending;
  }

  /**
  *Metodo auxiliar para el metodo getBcOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function bcOrdersByType()
  {
    $query = DB::table('users')
      ->join('bc_orders','bc_orders.user_id','=','users.id')
      ->join('regions','regions.id','=','users.region_id')
      ->where('bc_orders.created_at','>=',Input::get('since'))
      ->where('bc_orders.updated_at','<=',Input::get('until'));

    if(Input::has('divisional_id')){
      $query->where('users.divisional_id','=',Input::get('divisional_id'));
    }

    if (Input::has('num_pedido')){
      $query->where('bc_orders.id','like','%'.Input::get('num_pedido').'%');
    }

    if(Input::has('region_id')){
      $query->where('users.region_id',Input::get('region_id'));
    }
    $orders_by_type[] = [];

    if(Input::has('type')){
      switch(Input::get('type')){
        case "1":
          $q1 = clone $query;
          $total_bc_orders = $q1->count();
          $orders_by_type[0] = ["Tarjetas presentación",$total_bc_orders];
          break;
        case "2":
          $q2 = clone $query;
          $bc_orders_blank_cards = $q2->join('blank_cards_bc_order','blank_cards_bc_order.bc_order_id','=','bc_orders.id')->count();
          $orders_by_type[1] = ["Tarjetas Blancas",$bc_orders_blank_cards];
          break;
        case "3":
          $q4 = clone $query;
          $bc_orders_extras_talento = $q4->join('bc_orders_extras','bc_orders_extras.bc_order_id','=','bc_orders.id')->whereNotNull('bc_orders_extras.talento_nombre')->count();  
          $orders_by_type[2] =["Extras Talento",$bc_orders_extras_talento];
          break;
        case "4":
          $q3 = clone $query;
          $bc_orders_extras_gerente = $q3->join('bc_orders_extras','bc_orders_extras.bc_order_id','=','bc_orders.id')->whereNotNull('bc_orders_extras.gerente_nombre')->count();
          $orders_by_type[3] = ["Extras Gerente",$bc_orders_extras_gerente];
          break;
      }
    }else{

          $q1 = clone $query;
          $total_bc_orders = $q1->count();
          $orders_by_type[0] = ["Tarjetas presentación",$total_bc_orders];
          
          $q2 = clone $query;
          $bc_orders_blank_cards = $q2->join('blank_cards_bc_order','blank_cards_bc_order.bc_order_id','=','bc_orders.id')->count();
          $orders_by_type[1] = ["Tarjetas Blancas",$bc_orders_blank_cards];

          $q4 = clone $query;
          $bc_orders_extras_talento = $q4->join('bc_orders_extras','bc_orders_extras.bc_order_id','=','bc_orders.id')->whereNotNull('bc_orders_extras.talento_nombre')->count();  
          $orders_by_type[2] =["Extras Talento",$bc_orders_extras_talento];

          $q3 = clone $query;
          $bc_orders_extras_gerente = $q3->join('bc_orders_extras','bc_orders_extras.bc_order_id','=','bc_orders.id')->whereNotNull('bc_orders_extras.gerente_nombre')->count();
          $orders_by_type[3] = ["Extras Gerente",$bc_orders_extras_gerente];

    }
    
    return $orders_by_type;
  }


  /**
  *Metodo auxiliar para el metodo getBcOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function ordersByDivisional($report){
    $orders_category = clone $report;
    $orders_category->join('divisionals_users','users.divisional_id','=','divisionals_users.divisional_id')
                    ->join('divisionals','divisionals.id','=','divisionals_users.divisional_id');

    
    
    $orders_category = $orders_category->select(DB::raw('count(users.divisional_id) as QUANTITY,divisionals.name as NAME'))
                                       ->groupBy('users.divisional_id')
                                       ->get();
  
    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
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
      business_cards.direccion_alternativa AS DIRECCION_ALTERNATIVA")
      ->join('business_cards', 'business_cards.id', '=', 'bc_order_business_card.business_card_id')
      ->join('bc_orders', 'bc_orders.id', '=', 'bc_order_business_card.bc_order_id')
      ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
      ->whereNull('bc_orders.deleted_at')
      ->where('bc_orders.created_at','>=',Input::get('since'))
      ->where('bc_orders.updated_at','<=',Input::get('until')
    );

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
      '' AS DIRECCION_ALTERNATIVA")
      ->join('bc_orders', 'bc_orders.id', '=', 'blank_cards_bc_order.bc_order_id')
      ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
      ->whereNull('bc_orders.deleted_at')
      ->where('bc_orders.created_at','>=',Input::get('since'))
      ->where('bc_orders.updated_at','<=',Input::get('until')
    );

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
      'Atracción de talento' AS PUESTO_ATRACCION_GERENTE")
      ->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id')
      ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
      ->whereNull('bc_orders.deleted_at')
      ->where('bc_orders_extras.talento_nombre', '!=', "''")
      ->whereNotNull('bc_orders_extras.talento_nombre')
      ->where('bc_orders.created_at','>=',Input::get('since'))
      ->where('bc_orders.updated_at','<=',Input::get('until')
    );

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
      'Gerente comercial' AS PUESTO_ATRACCION_GERENTE")
      ->join('bc_orders', 'bc_orders.id', '=', 'bc_orders_extras.bc_order_id')
      ->leftJoin('users', 'users.id', '=', 'bc_orders.user_id')
      ->whereNull('bc_orders.deleted_at')
      ->where('bc_orders_extras.gerente_nombre', '!=', "''")
      ->whereNotNull('bc_orders_extras.gerente_nombre')
      ->where('bc_orders.created_at','>=',Input::get('since'))
      ->where('bc_orders.updated_at','<=',Input::get('until')
    );

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

    if(Input::has('divisional_id')){
      $query->where('users.divisional_id',Input::get('divisional_id'));
    }

    if (Input::has('num_pedido')){
      $query->where('bc_orders.id','like','%'.Input::get('num_pedido').'%');
    }

    if(Input::has('region_id')){
      $query->where('users.region_id',Input::get('region_id'));
    }

    $query->join('regions','regions.id','=','users.region_id');

    $orders_by_region = $this->ordersByRegion($query);
  

    $orders_status = $this->bcOrdersStatus($query);
    $orders_by_divisional = $this->ordersByDivisional($query);

    $orders_by_type = $this->bcOrdersByType();

    
    $q = clone $query;
    $item = $q->first();

    $headers = $item ?  array_keys(get_object_vars( $item )) : [];


    if(Input::has("ver-reporte")){
        
    }else{

      if(Request::ajax()){
        
        $orders_full = clone $query;

        return Response::json([
          'status' => 200,
          'orders' => $query->get(),
          'orders_full' => $orders_full->paginate(10)->toJson(),
          'headers' => $headers,
          'orders_status' => $orders_status,
          'orders_by_region' => $orders_by_region,
          'orders_by_type' => $orders_by_type,
          'orders_by_divisional' => $orders_by_divisional
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
           $excel->sheet('hoja 1',function($sheet)use($result){
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
  }


  /**
  *Metodo auxiliar para el metodo getFurnituresOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function furnituresOrdersStatus($report){
    $orders = clone $report;

    $orders = $orders->select(DB::raw('count(furniture_orders.status) as STATUS'))
                     ->groupBy('furniture_orders.status')
                     ->get();

    $orders_deliver_pending = [];

    foreach ($orders as $order) 
    {
     $orders_deliver_pending[] = $order->STATUS;
    }                                       
    return $orders_deliver_pending;
  }
  
  /**
  *Metodo auxiliar para el metodo getFurnituresOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function furnituresOrdersByDivisional($report){
    $orders_category = clone $report;
    $orders_category->join('divisionals_users','users.divisional_id','=','divisionals_users.divisional_id')
                    ->join('divisionals','divisionals.id','=','divisionals_users.divisional_id');

    
    
    $orders_category = $orders_category->select(DB::raw('count(users.divisional_id) as QUANTITY,divisionals.name as NAME'))
                                       ->groupBy('users.divisional_id')
                                       ->get();
    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
  }

    /**
  *Metodo auxiliar para el metodo getFurnituresOrdersReport
  */
  public function furnituresOrdersByCategory($report)
  {
    $orders_category = clone $report;

    $orders_category = $orders_category->select(DB::raw('count(furniture_categories.id) as QUANTITY,furniture_categories.name as NAME'))
                                       ->groupBy('furniture_categories.id')
                                       ->get();

    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
  }

    /**
  *Metodo auxiliar para el metodo getFurnituresOrdersReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function furnitureExpensesByRegion($report)
  {
    $expenses = clone $report;

    $expenses = $expenses->select(DB::raw('SUM(furnitures.unitary * furniture_furniture_order.quantity) as EXPENSIVE,regions.name as NAME'))
                         ->groupBy('regions.id')
                         ->get();

    $expenses_by_regions = [];

    foreach ($expenses as $expense) 
    {
     $expenses_by_regions[] = [$expense->NAME,$expense->EXPENSIVE];                     
    }                                       

    return $expenses_by_regions; 
  }

  public function getFurnituresOrdersReport()
  {
    ini_set('max_execution_time','300');
      $query = DB::table(DB::raw("(SELECT @rownum:=0) r, furniture_furniture_order"))->select(DB::raw("
      furniture_orders.created_at as FECHA_PEDIDO,
      '9999999999999990000000000' AS EIP_CTL_ID,
      1 as LOADER_REQ,
      'BPO' as SYSTEM_SOURCE,
      'PAF01' as LOADER_BU,
      @rownum:=@rownum+1 as GROUP_SEQ_NUM,
      'KA003035' as REQUESTOR_ID,
      DATE_FORMAT( NOW(), '%d/%m/%Y') as DUE_DT,
      furnitures.sku as INV_ITEM_ID,
      furnitures.name as DESCR254_MIXED,
      furnitures.measure_unit as UNIT_OF_MEASURE,
      furniture_furniture_order.quantity as QTY_REQ,
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
      users.linea_negocio as LINEA_NEGOCIO,
      users.ccosto as CCOSTO,
      @rownum as LINE_NBR,
      'Y' as CALC_PRICE_FLG,
      '' as CAP_NUM,
      '' as SHIP_TO_CUST_ID,
      'KA003035' as INTROD,
      furniture_categories.name as CATEGORY
      
      "))
      ->join('furnitures', 'furnitures.id', '=', 'furniture_furniture_order.furniture_id')
      ->join('furniture_orders', 'furniture_orders.id' , '=', 'furniture_furniture_order.furniture_order_id')
      ->leftJoin('users', 'users.id', '=', 'furniture_orders.user_id')
      ->leftJoin('furniture_categories', 'furnitures.furniture_category_id', '=', 'furniture_categories.id')
      ->orderBy('furniture_orders.id')
      ->whereNull('furniture_orders.deleted_at')
      ->where('furniture_orders.created_at','>=',Input::get('since'))
      ->where('furniture_orders.updated_at','<=',Input::get('until'));

    if(Input::has('gerencia')){
      $query->where('users.id','=',Input::get('gerencia'));
    }
    
    if(Input::has('category_id')){
      $category = Input::get('category_id') + 1;

      $query->where('furniture_categories.id','=',$category); 
    }

    if(Input::has('divisional_id')){
      $query->where('users.divisional_id',Input::get('divisional_id'));
    }

    if (Input::has('linea_negocio')) {
      $query->where('linea_negocio',Input::get('linea_negocio'));
    }

    $query->join('regions','regions.id','=','users.region_id');
    $orders_status = $this->furnituresOrdersStatus($query);
    $orders_by_divisional = $this->furnituresOrdersByDivisional($query);
    $orders_by_region = $this->ordersByRegion($query);
    $orders_by_category = $this->furnituresOrdersByCategory($query);
    $expenses_by_region = $this->furnitureExpensesByRegion($query);

    $q = clone $query;
    $orders_full = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
    if(Request::ajax()){
      $items = $query->get();
      return Response::json([
        'status' => 200,
        'orders' => $items,
        'orders_full' => $orders_full->paginate(10)->toJson(),
        'headers' => $headers,
        'orders_status' => $orders_status,
        'orders_by_divisional' => $orders_by_divisional,
        'orders_by_region' => $orders_by_region,
        'orders_by_category' => $orders_by_category,
        'expenses_by_region' => $expenses_by_region

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
          Excel::create('reporte_papeleria'.$mes.'_'.$año , function($excel) use($result){
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
  }

  public function getUserOrdersReport()
  {

    ini_set('max_execution_time','300');
    $query = User::where('role','user_paper')->whereHas('orders', function($q){
      $q->where(DB::raw('YEAR(orders.updated_at)'), Input::get('year'))
        ->where(DB::raw('MONTH(orders.created_at)'), Input::get('month'));
    }, '=', 0)->orderBy('ccosto');

    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars($q->first())) : [];

    if(Request::ajax()){
      
      return Response::json([
        'status' => 200,
        'orders_full' => $query->paginate(10)->toJson(),
        'headers' => $headers
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";
      $result = [$headers];

      foreach($query->get() as $item){
        $itemArray = [];
        $itemArray['CENTRO_COSTO'] = $item->ccosto;
        $itemArray['GERENCIA'] = $item->gerencia;
        $itemArray['LINEA_DE_NEGOCIO'] = $item->linea_negocio;

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
    }
  }

  public function getProductOrdersReport()
  {
    ini_set('max_execution_time','300');
    $query = DB::table('products')
    ->leftJoin('order_product','products.id','=','product_id')
    ->leftJoin('orders','orders.id','=','order_id')
    ->select('products.id','name as NOMBRE','model as MEDIDA','description as CATEGORIA',DB::raw('SUM(quantity) as SOLICITADOS'))
    ->where(DB::raw('MONTH(orders.created_at)'), Input::get('month'))
    ->where(DB::raw('YEAR(orders.updated_at)'), Input::get('year'))
    ->where('quantity', '>', 0)
    ->where('category_id','=',Input::get('category_id'))
    ->whereNull('orders.deleted_at')
    ->groupBy('order_product.product_id');

    $q = clone $query;
    $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];

    $query->orderBy('SOLICITADOS','DESC');

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
        Excel::create('Reporte_Productos_'.$mes.'_'.$año , function($excel) use($result){
         $excel->sheet('hoja 1',function($sheet)use($result){
           $sheet->fromArray($result);
            });
          })->download('xls');
      }
      return Response::make($data, 200, $headers);
    }
  }




  /**
  *Metodo auxiliar para el metodo getBIReport
  */
  public function ordersByCategory($report)
  {
    $orders_category = clone $report;

    $orders_category = $orders_category->select(DB::raw('count(categories.id) as QUANTITY,categories.name as NAME'))
                                       ->groupBy('categories.id')
                                       ->get();

    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
  }

  /**
  *Metodo auxiliar para el metodo getBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function ordersByRegion($report)
  {
    $orders_region = clone $report;

    $orders_region = $orders_region->select(DB::raw('count(regions.id) as QUANTITY,regions.name as NAME'))
                                       ->groupBy('regions.id')
                                       ->get();

    $orders_by_regions = [];

    foreach ($orders_region as $order) 
    {
     $orders_by_regions[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_regions;
  }

  /**
  *Metodo auxiliar para el metodo getBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function expensesByRegion($report)
  {
    $expenses = clone $report;

    $expenses = $expenses->select(DB::raw('SUM(products.price * order_product.quantity) as EXPENSIVE,regions.name as NAME'))
                         ->groupBy('regions.id')
                         ->get();

    $expenses_by_regions = [];

    foreach ($expenses as $expense) 
    {
     $expenses_by_regions[] = [$expense->NAME,$expense->EXPENSIVE];                     
    }                                       

    return $expenses_by_regions; 
  }

  /**
  *Metodo auxiliar para el metodo getBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function ordersStatus($report)
  {
    $orders = clone $report;

    $orders = $orders->select(DB::raw('count(orders.status) as STATUS'))
                     ->groupBy('orders.status')
                     ->get();

    $orders_deliver_pending = [];

    foreach ($orders as $order) 
    {
     $orders_deliver_pending[] = $order->STATUS;
    }                                       
    
    return $orders_deliver_pending;
  }

  public function getBIReport()
  {
  
    $report = DB::table('users')->join('orders','orders.user_id','=','users.id')
                                ->join('order_product','order_product.order_id','=','orders.id')
                                ->join('products','order_product.product_id','=','products.id')
                                ->join('categories','products.category_id','=','categories.id')
                                ->join('regions','regions.id','=','users.region_id');

    if(Input::has('order_id')){
      $report->where('orders.id','like','%'.Input::get('order_id').'%');
    }

    if(Input::has('ccosto')){
      $report->where('users.ccosto','like','%'.Input::get('ccosto').'%');
    }

    if(Input::has('category_id')){
      $report->where('categories.id',Input::get('category_id'));
    }

    if(Input::has('product_id')){
      $report->where('product_id',Input::get('product_id'));
    }

    if(Input::has('since')){
      $report->where('orders.created_at','>=',Input::get('since'));
    }

    if(Input::has('until')){
      $report->where('orders.created_at','<=',Input::get('until'));
    }

    if (Input::has('divisional_id')) {
      $report->where('users.divisional_id',Input::get('divisional_id'));
    }

    if(Input::has('region_id')){
      $report->where('users.region_id',Input::get('region_id'));
    }

    if(Input::has('status')){
      $report->where('orders.status',Input::get('status'));
    }

    $orders_by_category = $this->ordersByCategory($report);
    $orders_by_region = $this->ordersByRegion($report);
    $expenses_by_region = $this->expensesByRegion($report);
    $orders_status = $this->ordersStatus($report);

    $report->select(DB::raw("orders.id as ORDEN,
                            products.name as PRODUCTO,
                            order_product.quantity as CANTIDAD,
                            order_product.quantity * products.price as TOTAL,
                            categories.name as CATEGORIA, 
                            users.ccosto as CCOSTO,
                            users.gerencia as GERENCIA,
                            users.linea_negocio as LINEA_NEGOCIO,
                            orders.created_at as FECHA,
                            users.email as CORREO,
                            orders.comments as COMENTARIOS,
                            categories.id as CATEGORIA_ID,
                            regions.id as REGION_ID,
                            CASE orders.status
                              when '0'
                              then 'PENDIENTE'
                              when '1'
                              then 'RECIBIDO'
                              when '2'
                              then 'RECIBIDO_INCOMPLETO'
                            END AS ESTADO
                            "));
  
    

    $q = clone $report;
    $headers = $report->count() > 0 ?  array_keys(get_object_vars($q->first())) : [];
    $orders_full = clone $report;

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'headers' => $headers,
        'orders_by_category' => $orders_by_category,
        'orders_by_region' => $orders_by_region,
        'expenses_by_region' => $expenses_by_region,
        'orders_status' => $orders_status,
        'report' => $report->get(),
        'orders_full' => $orders_full->paginate(10)->toJson()
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";
      $result = [$headers];
      foreach($report->get() as $item){
          $itemArray = [];
        
        foreach($headers as $header){
          $itemArray[] = $item->{$header};
        }
        
          $result[] = $itemArray;
      }
      if($result){
        Excel::create('Reporte_BI', function($excel) use($result){
         $excel->sheet('hoja 1',function($sheet)use($result){
           $sheet->fromArray($result);
         });
        })->download('xls');   
      }
    }      
  }
 
  public function getBIAutocomplete()
  {
    
    $orders = Order::all()->lists('id');
    $ccostos = User::all()->lists('ccosto');
 

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders' => $orders,
        'ccostos' => $ccostos
      ]);
    }
  }

  public function getBIMacAutocomplete()
  {
    
    $orders = Order::all()->lists('id');
    $ccostos = User::all()->lists('ccosto');
 

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders' => $orders,
        'ccostos' => $ccostos
      ]);
    }
  }

/**
  *=============================================================MAC REPORTS ==================================================
  
  *Metodo auxiliar para el metodo getMacBIReport 
  */
  public function ordersByMacCategory($report)
  {
    $orders_category = clone $report;
    $orders_category = $orders_category->select(DB::raw('count(mac_categories.id) as QUANTITY,mac_categories.name as NAME'))
                                       ->groupBy('mac_categories.id')
                                       ->get();

    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
  }



  /**
  *Metodo auxiliar para el metodo getMacBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function ordersMacStatus($report)
  {
    $orders = clone $report;

    $orders = $orders->select(DB::raw('count(mac_orders.status) as STATUS'))
                     ->groupBy('mac_orders.status')
                     ->get();

    $orders_deliver_pending = [];

    foreach ($orders as $order) 
    {
     $orders_deliver_pending[] = $order->STATUS;
    }                                       
    
    return $orders_deliver_pending;
  }

  /**
  *Metodo auxiliar para el metodo getBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function expensesByMacRegion($report)
  {
    $expenses = clone $report;

    $expenses = $expenses->select(DB::raw('SUM(mac_products.price * mac_order_mac_product.quantity) as EXPENSIVE,regions.name as NAME'))
                         ->groupBy('regions.id')
                         ->get();

    $expenses_by_regions = [];

    foreach ($expenses as $expense) 
    {
     $expenses_by_regions[] = [$expense->NAME,$expense->EXPENSIVE];                     
    }                                       

    return $expenses_by_regions; 
  }

  public function getMacBIReport()
  {
  
    $report = DB::table('users')->join('mac_orders','mac_orders.user_id','=','users.id')
                                ->join('mac_order_mac_product','mac_order_mac_product.mac_order_id','=','mac_orders.id')
                                ->join('mac_products','mac_order_mac_product.mac_product_id','=','mac_products.id')
                                ->join('mac_categories','mac_products.mac_category_id','=','mac_categories.id')
                                ->join('regions','regions.id','=','users.region_id');

    if(Input::has('order_id')){
      $report->where('mac_orders.id','like','%'.Input::get('order_id').'%');
    }

    if(Input::has('ccosto')){
      $report->where('users.ccosto','like','%'.Input::get('ccosto').'%');
    }

    if(Input::has('category_id')){
      $report->where('mac_categories.id',Input::get('category_id'));
    }

    if(Input::has('product_id')){
      $report->where('mac_product_id',Input::get('product_id'));
    }

    if(Input::has('since')){
      $report->where('mac_orders.created_at','>=',Input::get('since'));
    }

    if(Input::has('until')){
      $report->where('mac_orders.created_at','<=',Input::get('until'));
    }

    if (Input::has('divisional_id')) {
      $report->where('users.divisional_id',Input::get('divisional_id'));
    }

    if(Input::has('region_id')){
      $report->where('users.region_id',Input::get('region_id'));
    }

    if(Input::has('status')){
      $report->where('mac_orders.status',Input::get('status'));
    }

    $orders_by_category = $this->ordersByMacCategory($report);
    $orders_by_region = $this->ordersByRegion($report);
    $expenses_by_region = $this->expensesByMacRegion($report);
    $orders_status = $this->ordersMacStatus($report);

    $report->select(DB::raw("mac_orders.id as ORDEN,
                            mac_products.name as PRODUCTO,
                            mac_order_mac_product.quantity as CANTIDAD,
                            mac_order_mac_product.quantity * mac_products.price as TOTAL,
                            mac_categories.name as CATEGORIA, 
                            users.ccosto as CCOSTO,
                            users.gerencia as GERENCIA,
                            users.linea_negocio as LINEA_NEGOCIO,
                            mac_orders.created_at as FECHA,
                            users.email as CORREO,
                            mac_orders.comments as COMENTARIOS,
                            mac_categories.id as CATEGORIA_ID,
                            regions.id as REGION_ID,
                            CASE mac_orders.status
                              when '0'
                              then 'PENDIENTE'
                              when '1'
                              then 'RECIBIDO'
                              when '2'
                              then 'RECIBIDO_INCOMPLETO'
                            END AS ESTADO
                            "))->orderBy('mac_orders.created_at','desc');
    $q = clone $report;
    $headers = $report->count() > 0 ?  array_keys(get_object_vars($q->first())) : [];
    $orders_full = clone $report;

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'headers' => $headers,
        'orders_by_category' => $orders_by_category,
        'orders_by_region' => $orders_by_region,
        'expenses_by_region' => $expenses_by_region,
        'orders_status' => $orders_status,
        'report' => $report->get(),
        'orders_full' => $orders_full->paginate(10)->toJson()
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";
      $result = [$headers];
      foreach($report->get() as $item){
          $itemArray = [];
        
        foreach($headers as $header){
          $itemArray[] = $item->{$header};
        }
        
          $result[] = $itemArray;
      }
      if($result){
        Excel::create('Reporte_Mac_BI', function($excel) use($result){
         $excel->sheet('hoja 1',function($sheet)use($result){
           $sheet->fromArray($result);
         });
        })->download('xls');   
      }
    }      
  }




/**
  *=============================================================Corporation REPORTS ==================================================
  
*/

  public function ordersByCorporationCategory($report)
  {
    $orders_category = clone $report;
    $orders_category = $orders_category->select(DB::raw('count(corporation_categories.id) as QUANTITY,corporation_categories.name as NAME'))
                                       ->groupBy('corporation_categories.id')
                                       ->get();

    $orders_by_category = [];

    foreach ($orders_category as $order) 
    {
     $orders_by_category[] = [$order->NAME,$order->QUANTITY];                     
    }                                       

    return $orders_by_category;
  }



  /**
  *Metodo auxiliar para el metodo getCorporationBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function ordersCorporationStatus($report)
  {
    $orders = clone $report;

    $orders = $orders->select(DB::raw('count(corporation_orders.status) as STATUS'))
                     ->groupBy('corporation_orders.status')
                     ->get();

    $orders_deliver_pending = [];

    foreach ($orders as $order) 
    {
     $orders_deliver_pending[] = $order->STATUS;
    }                                       
    
    return $orders_deliver_pending;
  }

  /**
  *Metodo auxiliar para el metodo getBIReport
  *Recibe una consulta ya con un reporte donde se tuvo que haber seleccionado la tabla regions.
  */
  public function expensesByCorporationRegion($report)
  {
    $expenses = clone $report;

    $expenses = $expenses->select(DB::raw('SUM(corporation_products.price * corporation_order_corporation_product.quantity) as EXPENSIVE,regions.name as NAME'))
                         ->groupBy('regions.id')
                         ->get();

    $expenses_by_regions = [];

    foreach ($expenses as $expense) 
    {
     $expenses_by_regions[] = [$expense->NAME,$expense->EXPENSIVE];                     
    }                                       

    return $expenses_by_regions; 
  }

  public function getCorporationBIReport()
  {
  
    $report = DB::table('users')->join('corporation_orders','corporation_orders.user_id','=','users.id')
                                ->join('corporation_order_corporation_product','corporation_order_corporation_product.corporation_order_id','=','corporation_orders.id')
                                ->join('corporation_products','corporation_order_corporation_product.corporation_product_id','=','corporation_products.id')
                                ->join('corporation_categories','corporation_products.corporation_category_id','=','corporation_categories.id')
                                ->join('regions','regions.id','=','users.region_id');

    if(Input::has('order_id')){
      $report->where('corporation_orders.id','like','%'.Input::get('order_id').'%');
    }

    if(Input::has('ccosto')){
      $report->where('users.ccosto','like','%'.Input::get('ccosto').'%');
    }

    if(Input::has('category_id')){
      $report->where('corporation_categories.id',Input::get('category_id'));
    }

    if(Input::has('product_id')){
      $report->where('corporation_product_id',Input::get('product_id'));
    }

    if(Input::has('since')){
      $report->where('corporation_orders.created_at','>=',Input::get('since'));
    }

    if(Input::has('until')){
      $report->where('corporation_orders.created_at','<=',Input::get('until'));
    }

    if (Input::has('divisional_id')) {
      $report->where('users.divisional_id',Input::get('divisional_id'));
    }

    if(Input::has('region_id')){
      $report->where('users.region_id',Input::get('region_id'));
    }

    if(Input::has('status')){
      $report->where('corporation_orders.status',Input::get('status'));
    }

    $orders_by_category = $this->ordersByCorporationCategory($report);
    $orders_by_region = $this->ordersByRegion($report);
    $expenses_by_region = $this->expensesByCorporationRegion($report);
    $orders_status = $this->ordersCorporationStatus($report);

    $report->select(DB::raw("corporation_orders.id as ORDEN,
                            corporation_products.name as PRODUCTO,
                            corporation_order_corporation_product.quantity as CANTIDAD,
                            corporation_order_corporation_product.quantity * corporation_products.price as TOTAL,
                            corporation_categories.name as CATEGORIA, 
                            users.ccosto as CCOSTO,
                            users.gerencia as GERENCIA,
                            users.linea_negocio as LINEA_NEGOCIO,
                            corporation_orders.created_at as FECHA,
                            users.email as CORREO,
                            corporation_orders.comments as COMENTARIOS,
                            corporation_categories.id as CATEGORIA_ID,
                            regions.id as REGION_ID,
                            CASE corporation_orders.status
                              when '0'
                              then 'PENDIENTE'
                              when '1'
                              then 'RECIBIDO'
                              when '2'
                              then 'RECIBIDO_INCOMPLETO'
                            END AS ESTADO
                            "))->orderBy('corporation_orders.created_at','desc');
    $q = clone $report;
    $headers = $report->count() > 0 ?  array_keys(get_object_vars($q->first())) : [];
    $orders_full = clone $report;

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'headers' => $headers,
        'orders_by_category' => $orders_by_category,
        'orders_by_region' => $orders_by_region,
        'expenses_by_region' => $expenses_by_region,
        'orders_status' => $orders_status,
        'report' => $report->get(),
        'orders_full' => $orders_full->paginate(10)->toJson()
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";
      $result = [$headers];
      foreach($report->get() as $item){
          $itemArray = [];
        
        foreach($headers as $header){
          $itemArray[] = $item->{$header};
        }
        
          $result[] = $itemArray;
      }
      if($result){
        Excel::create('Reporte_corporation_BI', function($excel) use($result){
         $excel->sheet('hoja 1',function($sheet)use($result){
           $sheet->fromArray($result);
         });
        })->download('xls');   
      }
    }      
  }

  public function getBICorporationAutocomplete()
  {
    
    $orders = CorporationOrder::all()->lists('id');
    $ccostos = User::all()->lists('ccosto');
 
    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders' => $orders,
        'ccostos' => $ccostos
      ]);
    }
  }

  public function getTotalUsersReport()
  {
    $users =  User::select('*');

    foreach ($users as $user) {
      $user->pedidos = $user->orders->count();
      $user->pendientes = $user->orders()->where('status',0)->count();
      $user->completos = $user->orders()->where('status', 1)->count();
      $user->incompletos = $user->orders()->where('status',2)->count();
    }

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders_full' => $users->paginate(10)->toJson(),
        ]);
      }
  }

  public function getGeneralRequestExcel()
  {
    $general_request = GeneralRequest::select('*');
    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders_full' => $general_request->paginate(10)->toJson(),
      ]);
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
        'PERFIL' => $perfil,
        'Email' => $user->email
      ];

    }

    $datetime = \Carbon\Carbon::now()->format('YmdHi');
    Excel::create('Reporte_usuarios_'.$datetime, function($excel) use($result){
      $excel->sheet('Usuarios',function($sheet)use($result){
        $sheet->fromArray($result);
      });
    })->download('xls');
  }

  public function getTotalUsersExcel()
  {
    $users =  User::all();

     foreach ($users as $user) {
        $user->pedidos = $user->orders->count();
        $user->pendientes = $user->orders()->where('status',0)->count();
        $user->completos = $user->orders()->where('status', 1)->count();
        $user->incompletos = $user->orders()->where('status',2)->count();

        $result[] = [
      'CENTRO_COSTOS' => $user->ccosto,
      'GERENCIA/NOMBRE' => $user->gerencia,
      'LINEA_DE_NEGOCIO' => $user->linea_negocio,
      'PEDIDOS' => $user->pedidos,
      'COMPLETOS' => $user->completos,
      'INCOMPLETOS' => $user->incompletos,
      'PENDIENTES' => $user->pendientes
      ];
      }

    $datetime = \Carbon\Carbon::now()->format('YmdHi');
    Excel::create('Reporte_usuarios_'.$datetime, function($excel) use($result){
      $excel->sheet('Usuarios',function($sheet)use($result){
        $sheet->fromArray($result);
      });
    })->download('xls');
  }

  public function getGeneralRequestsExcel()
  {
    $requests =  GeneralRequest::all();
   
   foreach ($requests as $request) {
    $average = $request->satisfaction_survey ? $request->satisfaction_survey->average : 0;
    
    $general_request_products = GeneralRequestProduct::where('general_request_id','=',$request->id)->first();

        $result[] = [
          '# SOLUCIÓN' => $request->id,
          'TITULO PROYECTO' => $request->project_title,
          'NOMBRE' => $request->employee_name,
          'NUMERO' => $request->employee_number,
          'ESTATUS' => $request->status_str,
          'PRESUPUESTO' => $general_request_products->quantity * $general_request_products->unit_price,
          'FECHA DE SOLICITUD' => $request->project_date->format('d-m-Y'),
          'FECHA DE INICIO' => $request->project_date->format('d-m-Y'),
          'FECHA DE ENTREGA' => $request->deliver_date->format('d-m-Y'),
          'COMENTARIOS' => $request->comments,
          'PROMEDIO'  =>  $average,
        ];
    }

    $datetime = \Carbon\Carbon::now()->format('YmdHi');
    Excel::create('Reporte_solicitudes_generales_'.$datetime, function($excel) use($result){
      $excel->sheet('Solicitudes',function($sheet)use($result){
        $sheet->fromArray($result);
      });
    })->download('xls');
  }


  public function getGeneralRequestReport()
  {
    $request = GeneralRequest::all();
    
    $request_products = $request->generalRequestProducts();
    $request->average = $request->satisfactionSurvey ? $request->satisfactionSurvey->average: 0 ;    

    if($request){
      return Response::json([
        'status' => 200,
        'request' => $request->toArray(),
        'request_products' => $request_products->toArray(),
      ]);
    }
  }

  public function getFurnituresSubcategories($category_id)
  {
    $subcategories = FurnitureCategory::find($category_id)->furniture_subcategories;
    if(Request::ajax()){
      return Response::json([
        'subcategories' => $subcategories,
        'status' => '200'
        ]);
    }
  }

  public function getIndexFurnitures($active_tab,$active_subtab)
  {  
  }


  public function getActiveUsersReport()
  {
     
      ini_set('max_execution_time','300');

      $query = DB::table('users')
      ->select('users.id','users.ccosto','gerencia','linea_negocio','role',
        DB::raw('sum(order_product.quantity) as quantity'))
      ->join('orders','users.id','=','orders.user_id')
      ->join('order_product','orders.id','= ','order_product.order_id')
      ->where(DB::raw('MONTH(orders.created_at)'), Input::get('month') )
      ->where(DB::raw('YEAR(orders.created_at)'), Input::get('year') )
      ->groupBy('users.id')
      ->orderBy('quantity', 'DESC');


      $q = clone $query;
      $headers = $query->count() > 0 ?  array_keys(get_object_vars( $q->first())) : [];
      if(Request::ajax() ){
        $paginate = clone $query;
        return Response::json([
          'status' => 200,
          'orders' => $query->paginate(10)->toJson(),
          'headers' => $headers,
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
}


  public function getAllProducts()
  {
    $report = DB::table('users')->join('orders','orders.user_id','=','users.id')
                                ->join('bc_orders','bc_orders.user_id','=','users.id')
                                ->join('furniture_orders','furniture_orders.user_id','=','users.id')
                                ->join('order_product','order_product.order_id','=','orders.id')
                                ->join('products','order_product.product_id','=','products.id')
                                ->join('categories','products.category_id','=','categories.id')
                                ->join('regions','regions.id','=','users.region_id');



    if(Input::has('category_id')){
      $report->where('categories.id',Input::get('category_id'));
    }


    if(Input::has('since')){
      $report->where('orders.created_at','>=',Input::get('since'));
    }

    if(Input::has('until')){
      $report->where('orders.created_at','<=',Input::get('until'));
    }


    if(Input::has('status')){
      $report->where('orders.status',Input::get('status'));
    }


    $report->select(DB::raw("orders.id as ORDEN,
                            products.name as PRODUCTO,
                            order_product.quantity as CANTIDAD,
                            order_product.quantity * products.price as TOTAL,
                            categories.name as CATEGORIA, 
                            users.ccosto as CCOSTO,
                            users.gerencia as GERENCIA,
                            users.linea_negocio as LINEA_NEGOCIO,
                            orders.created_at as FECHA,
                            users.email as CORREO,
                            orders.comments as COMENTARIOS,
                            categories.id as CATEGORIA_ID,
                            regions.id as REGION_ID,
                            orders.status as ESTADO"));

    $q = clone $report;
    $headers = $report->count() > 0 ?  array_keys(get_object_vars($q->first())) : [];


    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'headers' => $headers,
        'orders' => $report->paginate(10)->toJson()]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = str_putcsv($headers)."\n";
      $result = [$headers];
      foreach($report->get() as $item){
          $itemArray = [];
        
        foreach($headers as $header){
          $itemArray[] = $item->{$header};
        }
          $result[] = $itemArray;
      }
      if($result){
        Excel::create('Reporte_Todos los productos', function($excel) use($result){
         $excel->sheet('hoja 1',function($sheet)use($result){
           $sheet->fromArray($result);
         });
        })->download('xls');   
      }
    }      
  }


  public function getSurvey()
  {
    Log::debug(Input::all());
    

    $surveys = DB::table('satisfaction_surveys')->select(
        DB::raw('avg(question_one) as uno,
                  avg(question_two) as dos,
                  avg(question_three) as tres,
                  avg(question_four) as cuatro,
                  count(general_requests.id) as total'
                  ))
            ->join('general_requests','general_requests.id','=','satisfaction_surveys.general_request_id')  
            ->join('users','users.id','=','general_requests.manager_id');

    $comments = DB::table('satisfaction_surveys')->select(DB::raw('explain_1,explain_2,explain_3,explain_4,satisfaction_surveys.general_request_id'))
                  ->join('general_requests','general_requests.id','=','satisfaction_surveys.general_request_id')  
                  ->join('users','users.id','=','general_requests.manager_id');

    if(Input::has('since')){
      $surveys->where('general_requests.created_at','>',Input::get('since'));
      $comments->where('general_requests.created_at','>',Input::get('since'));

    }

    if(Input::has('until')){
      $surveys->where('general_requests.created_at','<',Input::get('until'));
      $comments->where('general_requests.created_at','<',Input::get('until'));
    }

    if(Input::has('gerencia')){
      $surveys->where('users.id','=',Input::get('gerencia'));
      $comments->where('users.id','=',Input::get('gerencia'));
    }

    if(Input::has('solicitud')){
      $surveys->where('general_requests.id','=',Input::get('solicitud'));
      $comments->where('general_requests.id','=',Input::get('solicitud'));
    }

    if(Input::has('encuesta')){
      $surveys->where('satisfaction_surveys.id','=',Input::get('encuesta'));
      $comments->where('satisfaction_surveys.id','=',Input::get('encuesta'));
    }

    if(Input::has('regional')){
      $surveys->where('users.region_id','=',Input::get('regional'));
      $comments->where('users.region_id','=',Input::get('regional'));
    }

      return Response::json([
        'status' => 200,
        'surveys' => $surveys->get(),
        'comments' => $comments->get()
      ]);
  }

  public function getCcostosAutocomplete()
  {
    
    $ccostos = User::where('role','=','manager')->lists('ccosto');
    $request = GeneralRequest::lists('id');
    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'ccostos' => $ccostos,
        'request' => $request,
      ]);
    }
  }

  public function postNotificationMarker()
  {
      $message = Message::find(Input::get('id'));
      $message->read_at = \Carbon\Carbon::now();
      if($message->save()){
        return Response::json([
          'status' => 200
        ]);
      }
  }


}