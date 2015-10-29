<?php

class OrdersController extends BaseController
{

  public function index()
  {
    return View::make('orders.index')->withOrders(Auth::user()->orders()->orderBy('created_at', 'desc')->get());
  }

  public function store()
  {

    if(Auth::user()->cart_products->count() == 0)
    {
      return Redirect::to('/')->withWarning('No puede enviarse un pedido con un carrito vacío');
    }
    $order = new Order(Input::all());
    $order->user_id = Auth::id();
    if($order->save()){
      foreach(Auth::user()->cart_products as $product)
      {
        $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        Auth::user()->cartProducts()->detach($product->id);
      }
    }

    if(Auth::user()->email != null){
        $user = Auth::user();
        $email_info = ['user' => Auth::user(),'order' => $order];
        Mail::send('admin::email_templates.furnitures',$email_info,function($message) use($user){
        $message->to("jona_54_.com@ciencias.unam.mx",$user->gerencia)->subject('Sobre su pedido');
      });   
    }
   
    return Redirect::to('/')->withSuccess('Se ha enviado su pedido satisfactoriamente');
  }

  public function show($order_id)
  {
    $order = Auth::user()->orders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('orders.show')->withOrder($order);
  }

  public function update($order_id)
  {
    $order = Auth::user()->orders()->find($order_id);

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

    return Redirect::to(action('OrdersController@index'))->withSuccess('Se ha actualizado su orden');
  }

  public function destroy($order_id)
  {
    $order = Order::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }
    if($order->status == 0) 
    {
      $order = $order->delete();
      return Redirect::to(action('OrdersController@index'))->withSuccess('Se ha eliminado la orden');  
    }else{
    
      return Redirect::back()->withErrors('El pedido ha sido aprobado no se puede eliminar');  

      }
   }


  public function postReceive($order_id)
  {
    $order = Order::find($order_id);
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
    return Redirect::to(action('OrdersController@index'))->withSuccess('Se ha actualizado la información');
  }


   
}
