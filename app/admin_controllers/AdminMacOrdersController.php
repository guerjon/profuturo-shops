<?php

class AdminMacOrdersController extends BaseController
{

  public function index()
  {
    if(Input::get('export') == 'xls'){
    $query = DB::table('users')->select('*','mac_orders.id as order_id','mac_orders.created_at as order_created_at')
              ->join('mac_orders','mac_orders.user_id','=','users.id')
              ->leftJoin('address','address.id','=','users.address_id')
              ->orderBy('mac_orders.created_at','desc');
    
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
        
    $gerencias = User::withTrashed()->where('role','user_mac')->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'ccosto');
    $orders = MacOrder::select(DB::raw('*,mac_orders.id as order_id,mac_orders.created_at as mac_created_at'))->orderBy('mac_orders.created_at', 'desc')->join('users','users.id','=','mac_orders.user_id');


    if(Input::has('ccosto'))
       $orders->where('users.ccosto','like','%'.Input::get('ccosto').'%');

    if(Input::has('gerencia'))
        $orders->where('users.ccosto', Input::get('gerencia'));

    if(Input::has('divisional_id'))
        $orders->where('users.divisional_id', Input::get('divisional_id'));
    if(Input::has('since'))
        $orders->where('mac_orders.created_at','>=',Input::get('since'));
    if(Input::has('to'))
      $orders->where('mac_orders.created_at','<=',Input::get('to'));

   
    return View::make('admin::mac_orders.index')->withOrders($orders->paginate(10))->withGerencias($gerencias);
  }

  public function show($order_id)
  {
    $order = MacOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('admin::mac_orders.show')->withOrder($order);
  }

   public function destroy($order_id)
  {
    $order = MacOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order = $order->delete();
    return Redirect::to(action('AdminMacOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }


}

