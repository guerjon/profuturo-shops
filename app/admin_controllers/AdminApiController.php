<?

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
    $query = DB::table(DB::raw("(SELECT @rownum:=0) r, order_product"))->select(
      DB::raw("
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
      'JC005819' as INTROD
      ")
    )->join('products', 'products.id', '=', 'order_product.product_id')
      ->join('orders', 'orders.id' , '=', 'order_product.order_id')
      ->leftJoin('users', 'users.id', '=', 'orders.user_id');
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
      foreach($query->get() as $item){
        $data .= self::convertObjectToCsv($item, $headers);
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
    $orders = BcOrder::with('businessCards', 'user')->where(DB::raw('MONTH(updated_at)'), Input::get('month'))
      ->where(DB::raw('YEAR(updated_at)'), Input::get('year'))->get();

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders' => $orders->toArray(),
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = "";
      $fields = ['id_order', 'gerencia', 'fecha', 'nombre', 'nombre_puesto', 'email', 'telefono', 'celular', 'web', 'ccosto', 'direccion'];
      foreach($orders as $order){
        foreach($order->business_cards as $card){
          $card->id_order = $order->id;
          $card->gerencia = $order->user->gerencia;
          $card->fecha = $order->updated_at->format('d/m/Y');
          $data .= self::convertObjectToCsv($card, $fields);
        }

      }

      Log::info($data);

      $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"reporte_pedidos_tp_{$datetime}.csv\"",
      );
      return Response::make($data, 200, $headers);
    }
  }

}
