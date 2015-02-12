<?

class AdminApiController extends AdminBaseController
{

  private function convertObjectToCsv($object, $fields)
  {
    $array = [];
    foreach($fields as $field)
    {
      try{
        $array[] = $object->getAttribute($field);
      }catch(Exception $e){
        Log::error($e);
        $array[] = "";
      }
    }
    
    return str_putcsv($array)."\n";
  }

  public function getOrdersReport()
  {
    $orders = Order::with('products', 'user')->where(DB::raw('MONTH(updated_at)'), Input::get('month'))
      ->where(DB::raw('YEAR(updated_at)'), Input::get('year'))->get();

    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'orders' => $orders->toArray(),
        ]);
    }else{

      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = "";
      $fields = ['ccosto', 'loader_bu', 'sku', 'name', 'measure_unit', 'quantity', 'price', 'currency', 'location', 'ship_to', 'dept_id'];
      foreach($orders as $order){
        foreach($order->products as $product){
          $product->ccosto = $order->user->ccosto;
          $product->loader_bu = 0;
          $product->price = 0;
          $product->location = 0;
          $product->ship_to = 0;
          $product->dept_id = 0;
          $product->quantity = $product->pivot->quantity;
          $product->currency = 'MXN';
          $data .= self::convertObjectToCsv($product, $fields);
        }

      }

      Log::info($data);

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
