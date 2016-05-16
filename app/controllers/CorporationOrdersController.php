<?php

class CorporationOrdersController extends \BaseController {

	public function index()
  {
    return View::make('corporation_orders.index')->withOrders(Auth::user()->CorporationOrders()->orderBy('created_at', 'desc')->get());
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
    $access = DateCorporation::where('since','<=',$today)->where('until','>=',$today)->count();

    \Log::debug($access);
    if($access <= 0)
      return Redirect::to('carrito-corporativo')->withWarning('Actualmente no se tiene permitido el envio productos, intente mas tarde.');


    if(Auth::user()->cart_corporation->count() == 0)
    {
      return Redirect::to('/')->withWarning('No puede enviarse un pedido con un carrito vacío');
    }


    //Direcciones y notificaciones
    if(!$address){
      if(Input::has('posible_cambio')){
        if(Auth::user()->address_id != 0 || Auth::user()->address == null)
          $address = new Address(['posible_cambio' => Input::get('posible_cambio')]);
        else{
          $address = Address::find(Auth::user()->address_id);
          $address->update(['posible_cambio' => Input::get('posible_cambio')]);
        }
          
        if($address->save()){
          //Al guardar el posible cambio lo asignamos al usuario
          $user = Auth::user();
          $user->address_id = $address->id;
          //Vamos a crear los mensajes para la notificacion

          
            if($user->save()){
              Log::debug("Se agrego una nueva notificación al usuario y se creo o modifico ");
            }
          
        }else{
            Log::debug($address->getErrors());
        }
      }else{
        return Redirect::back()->withErrors("El pedido no puede ser enviado sin dirección.");
      }
    }else{

      if(Auth::user()->address->domicilio == ""){
        return Redirect::back()->with(['errors' => ["El centro de costos ya tiene una dirección pero aun no ha sido aprobada por el administrador, se necesita su aprovación para continuar."]]);
      }

      if(strcmp(Input::get('domicilio_original'),Input::get('posible_cambio')) != 0){
        $address = Auth::user()->address;
        $address->posible_cambio = Input::get('posible_cambio');
        if($address->save()){
          //Al guardar el posible cambio lo asignamos al usuario
          $user->address_id = $address->id;
          //Vamos a crear los mensajes para la notificacion
        }else{
            Log::debug($address->getErrors());
        }        
      }

      if($user->save()){
        Log::debug("Se agrego una nueva notificación al usuario y se creo o modifico ");
      }
    }
    //orden

    $firstDay = \Carbon\Carbon::parse('first day of this month')->format('Y-m-d');
    $lastDay =   \Carbon\Carbon::parse('last day of this month')->format('Y-m-d');
    //Cuenta las ordenes que se han hecho en este mes
    $orders_by_month = DB::table('corporation_orders')
                        ->where('created_at','>=',$firstDay)
                        ->where('created_at','<',$lastDay)
                        ->where('user_id',Auth::user()->id)
                        ->count();
    
    $extra_order = $orders_by_month > 0 ? true : false;
    $order = new CorporationOrder(Input::except('domicilio_original','posible_cambio') + ['extra_order' => $extra_order]);
    $order->user_id = Auth::id();
    if($order->save()){
      foreach(Auth::user()->cart_corporation as $product)
      {
        $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity,'description' =>$product->pivot->description]);
        Auth::user()->cartCorporation()->detach($product->id);
      }
    }
   
    return Redirect::action('CorporationOrdersController@index')->withSuccess('Se ha enviado su pedido satisfactoriamente');
  }

  public function show($order_id)
  {

    $order = Auth::user()->CorporationOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    return View::make('corporation_orders.show')->withOrder($order);
  }

  public function update($order_id)
  {
    $order = Auth::user()->CorporationOrders()->find($order_id);

    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $order->status = Input::get('status');
    $order->save();

    if($order->status == 2){
      $complain = $order->order_complain == NULL ? new CorporationOrderComplain : $order->order_complain;
      $complain->complain = Input::get('complain');
      $complain->order_id = $order->id;
      $complain->save();
    }

    return Redirect::to(action('CorporationOrdersController@index'))->withSuccess('Se ha actualizado su orden');
  }

  public function destroy($order_id)
  {
    $order = CorporationOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }
    if($order->status == 0) 
    {
      $order = $order->delete();
      return Redirect::to(action('CorporationOrdersController@index'))->withSuccess('Se ha eliminado la orden');  
    }else{
    
      return Redirect::back()->withErrors('El pedido ha sido aprobado no se puede eliminar');  

      }
   }


  public function postReceive($order_id)
  {
    $order = CorporationOrder::find($order_id);
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
    return Redirect::to(action('CorporationOrdersController@index'))->withSuccess('Se ha actualizado la información');
  }



}
