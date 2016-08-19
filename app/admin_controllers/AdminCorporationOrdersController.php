<?php

class AdminCorporationOrdersController extends BaseController
{

  public function index()
  {

        
    $gerencias = User::withTrashed()->where('role','user_Corporation')->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'ccosto');
    $orders = CorporationOrder::select(DB::raw('*,corporation_orders.id as order_id,corporation_orders.created_at as corporation_created_at'))->orderBy('corporation_orders.created_at', 'desc')->join('users','users.id','=','corporation_orders.user_id');


    if(Input::has('ccosto'))
       $orders->where('users.ccosto','like','%'.Input::get('ccosto').'%');

    if(Input::has('gerencia'))
        $orders->where('users.ccosto', Input::get('gerencia'));

    if(Input::has('divisional_id'))
        $orders->where('users.divisional_id', Input::get('divisional_id'));
        
      
    $orders->where('corporation_orders.created_at','>=',Input::get('since',\Carbon\Carbon::now('America/Mexico_City')->subMonths(1)->format('Y-m-d')));
    $orders->where('corporation_orders.created_at','<=',Input::get('to',\Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d')));


    if(Input::get('export') == 'xls'){
      $headers = ['GERENCIA','CCOSTOS','NO_PEDIDO','COMENTARIOS','FECHA DE PEDIDO','ESTATUS','DOMICILIO'];
      $datetime = \Carbon\Carbon::now()->format('d-m-Y');
      
      Excel::create('Reporte_pedidos_corporativo_'.$datetime, function($excel) use($orders,$headers){
        $excel->sheet('Pedidos',function($sheet)use($orders,$headers){
        $sheet->appendRow($headers);
        

        foreach ($orders->get() as $request) {
          $status = "";
                if($request->status == 0)
                  $status = "Pendiente";
                elseif($request->status==1)
                  $status = "Recibido";
                elseif($request->status==2)
                  $status = "Recibido incompleto";

          $sheet->appendRow([
            $request->user->gerencia,
            $request->user->ccosto,
            $request->order_id,
            $request->comments,
            $request->corporation_created_at,
            $status,
            $request->user->address ? $request->user->address->domicilio : "N/A"
          ]); 
        }
        });
      })->download('xlsx');


    }
   
    return View::make('admin::corporation_orders.index')
      ->withOrders($orders->paginate(10))
      ->withGerencias($gerencias)
      ->withInput(Input::flash());
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

