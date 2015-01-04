<?php

class AdminOrdersController extends BaseController
{

  public function index()
  {
    return View::make('admin::orders.index')->withOrders(Order::orderBy('created_at', 'desc')->get());
  }

  public function show($order_id)
  {
    $order = Order::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('admin::orders.show')->withOrder($order);
  }

  public function update($order_id)
  {
    // $order = Order::find($order_id);
    // if(!$order){
    //   return Redirect::to('/')->withWarning('No se encontró la orden');
    // }
    //
    // $order->status = Input::get('status');
    // $order->save();
    //
    // if($order->status == 2){
    //   $complain = $order->order_complain == NULL ? new OrderComplain : $order->order_complain;
    //   $complain->complain = Input::get('complain');
    //   $complain->order_id = $order->id;
    //   $complain->save();
    // }
    //
    // return Redirect::to(action('OrdersController@index'))->withSuccess('Se ha actualizado su orden');
  }
}
