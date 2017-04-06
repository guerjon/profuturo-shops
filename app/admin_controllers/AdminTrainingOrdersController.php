<?php

class AdminTrainingOrdersController extends BaseController
{

  public function index()
  {

    if(Input::get('export') == 'xls'){
      $query = TrainingOrder::select(
                  DB::raw('training_orders.*,training_orders.id as order_id,training_orders.created_at as training_created_at'))
                  ->with('sede')
                  ->orderBy('training_orders.created_at', 'desc')
                  ->join('users','users.id','=','training_orders.user_id');
      
      $query = $this->filters($query);
    
      $q = clone $query;
      $headers = ['NOMBRE_CC','SEDE','NO_PEDIDO','COMENTARIOS','CREADO','STATUS','DIRECCIÓN'];
      $result = [$headers];

      foreach ($query->get() as $item) {
      $itemArray = [];
      $itemArray['NOMBRE_CC']    = $item->gerencia;
      $itemArray['SEDE']     = $item->sede ? $item->sede->name : ''; 
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

      $itemArray['DIRECCION'] = $item->sede ? $item->sede->address : "N/A";  
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
          
      $gerencias = User::withTrashed()->where('role','user_training')->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'ccosto');
      $orders = TrainingOrder::select(
                  DB::raw('training_orders.*,training_orders.id as order_id,training_orders.created_at as training_created_at'))
                  ->with('sede')
                  ->orderBy('training_orders.created_at', 'desc')
                  ->join('users','users.id','=','training_orders.user_id');

      $orders = $this->filters($orders);
   
    return View::make('admin::training_orders.index')
      ->withOrders($orders->paginate(10))
      ->withGerencias($gerencias);
  }

  public function show($order_id)
  {
    $order = TrainingOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('admin::training_orders.show')->withOrder($order);
  }

   public function destroy($order_id)
  {
    $order = TrainingOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order = $order->delete();
    return Redirect::to(action('AdminTrainingOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }

  private function filters($query){
    
      if(Input::has('ccosto'))
         $query->where('training_orders.ccosto','like','%'.Input::get('ccosto').'%');

      if(Input::has('gerencia'))
          $query->where('users.ccosto', Input::get('gerencia'));

      if(Input::has('divisional_id'))
          $query->where('users.divisional_id', Input::get('divisional_id'));
      if(Input::has('since'))
          $query->where('training_orders.created_at','>=',Input::get('since'));
      if(Input::has('to'))
        $query->where('training_orders.created_at','<=',Input::get('to'));

      return $query;
  }

}

