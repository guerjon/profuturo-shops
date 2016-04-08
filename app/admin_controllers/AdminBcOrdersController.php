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
           $sheet->fromArray($result);
        });
      })->download('xlsx');
    }

    }

    
    $orders = DB::table('bc_orders')->select('*','bc_orders.id as order_id')->join('users','users.id','=','bc_orders.user_id')->orderBy('bc_orders.created_at', 'desc');
    if(Input::has('ccosto'))
      $orders->where('users.id',Input::get('ccosto'));
    if(Input::has('gerencia'))
      $orders->where('users.id', Input::get('gerencia'));

    return View::make('admin::bc_orders.index')->withBcOrders($orders->get());
  }

  public function show($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);    
  	$blank_card = DB::table('blank_cards_bc_order')->where('bc_order_id', $bc_order_id)->first();
    $extra = $bc_order->extra; 
    return View::make('admin::bc_orders.show')->withBcOrder($bc_order)->withBlankCard($blank_card)->withExtra($extra);
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
