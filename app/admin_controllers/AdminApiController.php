<?

class AdminApiController extends AdminBaseController
{

  private function convertObjectToCsv($object, $fields)
  {
    $array = [];
    foreach($fields as $field)
    {
      try{
        $array[] = $object->attribute[$field];
      }catch(Exception $e){
        Log::error($e);
        $array[] = "";
      }
    }
    return str_putcsv($array);
  }

  public function getOrdersReport()
  {
    $orders = Order::with('products', 'user');

    if(Request::ajax()){
      $orders = $orders->paginate(20);

      return Response::json([
        'status' => 200,
        'orders' => $orders->toArray(),
        ]);
    }else{

      $orders = $orders->get();
      $datetime = \Carbon\Carbon::now()->format('YmdHi');
      $data = "";
      $fields = ['id'];
      foreach($orders as $order){
        $data .= self::convertObjectToCsv($order, $fields);
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

  }

}
