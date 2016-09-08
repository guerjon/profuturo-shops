<?php

class TrainingOrdersController extends \BaseController {

	public function index()
  {
    return View::make('training_orders.index')->withOrders(Auth::user()->TrainingOrders()->orderBy('created_at', 'desc')->get());
  }

  public function store()
  {

    $address = Auth::user()->address;
    $user = Auth::user();
    $values = [ 'sender_id' => Auth::user()->id,
                'receiver_id' => '1',
                'body' => "El usuario " + Auth::user()->ccosto + "ha solicitado un cambio de domicilio",
                'type' => 'pedidos'
              ];
    $today = \Carbon\Carbon::today();


    if(Auth::user()->cart_training->count() == 0)
    {
      return Redirect::to('/')->withWarning('No puede enviarse un pedido con un carrito vacío');
    }


    $firstDay = \Carbon\Carbon::parse('first day of this month')->format('Y-m-d');
    $lastDay =   \Carbon\Carbon::parse('last day of this month')->format('Y-m-d');
    //Cuenta las ordenes que se han hecho en este mes
    $orders_by_month = DB::table('training_orders')
                        ->where('created_at','>=',$firstDay)
                        ->where('created_at','<',$lastDay)
                        ->where('user_id',Auth::user()->id)
                        ->count();
    
    $extra_order = $orders_by_month > 0 ? true : false;
    $order = new TrainingOrder(Input::except('domicilio_original','posible_cambio') + ['extra_order' => $extra_order]);
    $order->user_id = Auth::id();
    if($order->save()){
      foreach(Auth::user()->cart_training as $product)
      {
        $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity,'description' =>$product->pivot->description]);
        Auth::user()->cartTraining()->detach($product->id);
      }
    }
   
    return Redirect::action('TrainingOrdersController@index')->withSuccess('Se ha enviado su pedido satisfactoriamente');
  }

  public function show($order_id)
  {

    $order = Auth::user()->TrainingOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('training_orders.show')->withOrder($order);
  }

  public function update($order_id)
  {
    $order = Auth::user()->TrainingOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order->status = Input::get('status');
    $order->save();

    if($order->status == 2){
      $complain = $order->order_complain == NULL ? new TrainingOrderComplain : $order->order_complain;
      $complain->complain = Input::get('complain');
      $complain->order_id = $order->id;
      $complain->save();
    }

    return Redirect::to(action('TrainingOrdersController@index'))->withSuccess('Se ha actualizado su orden');
  }

  public function destroy($order_id)
  {
    $order = TrainingOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }
    if($order->status == 0) 
    {
      $order = $order->delete();
      return Redirect::to(action('TrainingOrdersController@index'))->withSuccess('Se ha eliminado la orden');  
    }else{
    
      return Redirect::back()->withErrors('El pedido ha sido aprobado no se puede eliminar');  

      }
   }


  public function postReceive($order_id)
  {
    $order = TrainingOrder::find($order_id);
    $complete = 1;
    foreach(Input::get('product') as $id => $product){
      $pivot = $order->products()->where('id', $id)->first()->pivot;
      $complete *= $product['status'];
      $pivot->status = $product['status'];
      $pivot->comments = $product['comments'];
      $pivot->save();
    }

    if($complete){
      $order->status = $complete;
    }else{
      $order->status = 2;
    }


    $order->receive_comments = Input::get('receive_comments');
    $order->save();
    return Redirect::to(action('TrainingOrdersController@index'))->withSuccess('Se ha actualizado la información');
  }



}
