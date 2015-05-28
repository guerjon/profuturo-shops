<?

class AdminBcOrdersController extends AdminBaseController{

  public function index()
  {
   if(Input::get('export') == 'xls'){
    $query =  BcOrder::orderBy('created_at', 'desc');
    

    $q = clone $query;
    $headers =  $headers = ['CLAVE_CC','CCOSTOS','NO_PEDIDO','COMENTARIOS','CREADO','STATUS'];
    $result = [$headers];

    foreach ($query->get() as $item) {
    $itemArray = [];
    $itemArray['GERENCIA']    = $item->user->gerencia;
    $itemArray['CCOSTOS']     = $item->user->ccosto;
    $itemArray['NO_PEDIDO']   = $item->id;
    $itemArray['COMENTARIOS'] = $item->comments;
    $itemArray['CREADO']      = $item->created_at->format('d-m-Y');
    if($item->status == 0){
        $itemArray['ESTATUS'] = 'PENDIENTE';
      }elseif($item->status == 1){
        $itemArray['ESTATUS'] = 'Recibido ';
      }elseif($item->status==2){
         $itemArray['ESTATUS'] = 'Recibido Incompleto';
      }elseif($item->status==2){
        $itemArray['ESTATUS'] = 'Recibido incompleto';
      }
     
    $result[] = $itemArray;
    }

    if($result){
      Excel::create('Reporte_productos',function($excel) use($result){
         $excel->sheet('Hoja_1', function($sheet) use($result) {
          Log::info($result);
           $sheet->fromArray($result);
        });
      })->download('xlsx');
    }

    }
    return View::make('admin::bc_orders.index')->withBcOrders(BcOrder::all());
  }

  public function show($bc_order_id)
  {
  	$blank_card = DB::table('blank_cards_bc_order')->where('bc_order_id', $bc_order_id)->first();
    return View::make('admin::bc_orders.show')->withBcOrder(BcOrder::find($bc_order_id))->withBlankCard($blank_card);
  }


  public function destroy($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    if(!$bc_order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $bc_order = $bc_order->delete();
    return Redirect::to(action('AdminBcOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }
}
