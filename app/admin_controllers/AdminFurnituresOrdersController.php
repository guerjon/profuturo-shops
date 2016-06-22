<?php

class AdminFurnituresOrdersController extends BaseController
{

  public function index()
  {
    if(Input::get('export') == 'xls'){
    $query =  FurnitureOrder::orderBy('created_at', 'desc')->with('user');
    
    $q = clone $query;
    $headers = ['NOMBRE_CC','CCOSTOS','NO_PEDIDO','COMENTARIOS','CREADO','STATUS'];
    $result = [$headers];

    foreach ($query->get() as $item) {
    $itemArray = [];
    $itemArray['NOMBRE_CC']    = $item->user->gerencia;
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
      Excel::create('Reporte_Ordenes_Mobiliario',function($excel) use($result){
         $excel->sheet('Hoja_1', function($sheet) use($result) {
          Log::info($result);
           $sheet->fromArray($result);
        });
      })->download('xlsx');
    }


    }

    $gerencias = User::withTrashed()->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'ccosto');
    $orders = DB::table('furniture_orders')
                ->select(DB::raw('*,furniture_orders.id as order_id,furniture_orders.created_at as order_created_at'))
                ->join('users','furniture_orders.user_id','=','users.id')
                ->orderBy('furniture_orders.created_at', 'desc');

    if(Input::has('ccosto'))
      $orders->where('users.ccosto','like','%'.Input::get('ccosto').'%');
    if(Input::has('gerencia'))
      $orders->where('ccosto', Input::get('gerencia'));
    if(Input::has('since'))
        $orders->where('furniture_orders.created_at','>=',Input::get('since'));
    if(Input::has('to'))
      $orders->where('furniture_orders.created_at','<=',Input::get('to'));

        
    return View::make('admin::furnitures_orders.index')->withOrders($orders->get())->withGerencias($gerencias);
  }

  public function show($order_id)
  {
    $order = FurnitureOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('admin::furnitures_orders.show')->withOrder($order);
  }

   public function destroy($order_id)
  {
    $order = FurnitureOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order = $order->delete();
    return Redirect::to(action('AdminFurnituresOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }


}
