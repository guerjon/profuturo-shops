<?php

class AdminCorporationOrdersController extends BaseController
{

  public function index()
  {
    if(Input::get('export') == 'xls'){
    $query = DB::table('users')->select('*','corporation_orders.id as order_id','corporation_orders.created_at as order_created_at')
              ->join('corporation_orders','corporation_orders.user_id','=','users.id')
              ->leftJoin('address','address.id','=','users.address_id')
              ->orderBy('corporation_orders.created_at','desc');
    
    $q = clone $query;
    $headers = ['NOMBRE_CC','CCOSTOS','NO_PEDIDO','COMENTARIOS','CREADO','STATUS','DIRECCIÓN'];
    $result = [$headers];

    foreach ($query->get() as $item) {
    $itemArray = [];
    $itemArray['NOMBRE_CC']    = $item->gerencia;
    $itemArray['CCOSTOS']     = $item->ccosto;
    $itemArray['NO_PEDIDO']   = $item->order_id;
    $itemArray['COMENTARIOS'] = $item->comments;
    $itemArray['CREADO']      = $item->order_created_at;

    if($item->status == 0){
        $itemArray['ESTATUS'] = 'PENDIENTE';
    }elseif($item->status == 1){
      $itemArray['ESTATUS'] = 'Recibido ';
    }elseif($item->status==2){
       $itemArray['ESTATUS'] = 'Recibido Incompleto';
    }elseif($item->status==2){
      $itemArray['ESTATUS'] = 'Recibido incompleto';
    }

    $itemArray['DIRECCION'] = $item->domicilio;  
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
        
    $gerencias = User::withTrashed()->where('role','user_Corporation')->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'ccosto');
    $orders = CorporationOrder::select(DB::raw('*,corporation_orders.id as order_id,corporation_orders.created_at as corporation_created_at'))->orderBy('corporation_orders.created_at', 'desc')->join('users','users.id','=','corporation_orders.user_id');


    if(Input::has('ccosto'))
       $orders->where('users.ccosto','like','%'.Input::get('ccosto').'%');

    if(Input::has('gerencia'))
        $orders->where('users.ccosto', Input::get('gerencia'));

    if(Input::has('divisional_id'))
        $orders->where('users.divisional_id', Input::get('divisional_id'));

   
    return View::make('admin::corporation_orders.index')->withOrders($orders->paginate(10))->withGerencias($gerencias);
  }

  public function show($order_id)
  {
    $order = CorporationOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('admin::corporation_orders.show')->withOrder($order);
  }

   public function destroy($order_id)
  {
    $order = CorporationOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order = $order->delete();
    return Redirect::to(action('AdminCorporationOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }


}

