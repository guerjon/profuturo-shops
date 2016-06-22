<?php

class OrderFurnituresController extends BaseController
{

  public function index()
  {
    return View::make('orders_furnitures.index')
    ->withOrders(Auth::user()->furnitureOrders()->orderBy('created_at', 'desc')->get());
  }

  public function store()
  {

    if(Auth::user()->cart_furnitures->count() == 0)
    {
      return Redirect::to('/')->withWarning('No puede enviarse un pedido con un carrito vacío');
    }
    Log::debug(Input::all());
    $order = new FurnitureOrder(Input::except('color'));
    $order->user_id = Auth::id();

    if($order->save()){
      if(Auth::user()->email != null){
          $user = Auth::user();
          $products = $order->furnitures();
          $email_info = ['user' => Auth::user(),'order' => $order,'products' => $products];

          Mail::send('admin::email_templates.orders_furniture',$email_info,function($message) use($user){
            $message->to(Auth::user()->email,$user->gerencia)->subject('Sobre su pedido');
          });   
      }   
      foreach(Auth::user()->cart_furnitures as $furniture)
      {
        $order->furnitures()->attach($furniture->id, ['quantity' => $furniture->pivot->quantity,
                                                    'company' => $furniture->pivot->company,
                                                    'assets' => $furniture->pivot->assets,
                                                    'ccostos' => $furniture->pivot->ccostos,
                                                    'color' => Input::get('color'),
                                                    'id_active' => $furniture->pivot->id_active,
                                                    ]);
        Auth::user()->cartFurnitures()->detach($furniture->id);
      }
    }
    return Redirect::to('/')->withSuccess('Se ha enviado su pedido satisfactoriamente');
  }

  public function show($order_id)
  {
    $order = Auth::user()->furnitureOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('orders_furnitures.show')->withOrder($order);
  }

  public function update($order_id)
  {
    $order = Auth::user()->furnitureOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order->status = Input::get('status');
    $order->save();

    if($order->status == 2){
      $complain = $order->order_complain == NULL ? new OrderComplain : $order->order_complain;
      $complain->complain = Input::get('complain');
      $complain->order_id = $order->id;
      $complain->save();
    }

    return Redirect::to(action('OrderFurnituresController@index'))->withSuccess('Se ha actualizado su orden');
  }

  public function destroy($order_id)
  {
    $order = FurnitureOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }
    if($order->status == 0) 
    {
      $order = $order->delete();
      return Redirect::to(action('OrderFurnituresController@index'))->withSuccess('Se ha eliminado la orden');  
    }else{
    
      return Redirect::back()->withErrors('El pedido ha sido aprobado no se puede eliminar');  

      }
   }


  public function postReceive($order_id)
  {
    $order = FurnitureOrder::find($order_id);
    $complete = 1;
    foreach(Input::get('furniture') as $id => $furniture){
      $pivot = $order->furnitures()->where('id', $id)->first()->pivot;
      $complete *= $furniture['status'];
      $pivot->status = $furniture['status'];
      $pivot->comments = $furniture['comments'];
      $pivot->save();
    }

    if($complete){
      $order->status = $complete;
    }else{
      $order->status = 2;
    }

    $order->comments = Input::get('comments');
    $order->save();
    return Redirect::to(action('OrderFurnituresController@index'))->withSuccess('Se ha actualizado la información');
  }


   
}
