<?

class BcOrdersController extends BaseController{

  public function index()
  {
    return View::make('bc_orders.index')->withBcOrders(Auth::user()->bc_orders);
  }

  public function show($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    if(!$bc_order){
      return Redirect::to(action('BcOrdersController@index'))->withErrors('No se encontró la orden');
    }
    if($bc_order->confirmed){
      return View::make('bc_orders.show')->withBcOrder($bc_order);
    }else{
      return Redirect::to(action('BcOrdersController@edit', [$bc_order->id]))->withInfo('Por favor, confirme los datos de las tarjetas para enviar la orden');
    }
  }

  public function store()
  {
    $cards = Input::get('cards', []);
    if(count($cards) < 1){
      return Redirect::to(URL::previous())->withErrors('No se selecciono ninguna tarjeta');
    }
    $bc_order = BcOrder::create([
      'user_id' => Auth::id(),
      ]);

    $quantities = Input::get('quantities', []);
    foreach($cards as $card_id)
    {
      $bc_order->businessCards()->attach($card_id, ['quantity' => @$quantities[$card_id]*100 ]);
    }

    return Redirect::to(action('BcOrdersController@edit', [$bc_order->id]))->withInfo('Por favor, confirme los datos de las tarjetas para enviar la orden');
  }

  public function edit($bc_order_id){
    $bc_order = BcOrder::find($bc_order_id);
    return View::make('bc_orders.edit')->withBcOrder($bc_order);
  }

  public function update($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    $bc_order->confirmed = true;
    $bc_order->comments = Input::get('comments');
    foreach(Input::get('card', []) as $id => $card){
      $bc = BusinessCard::find($id);
      if($bc){
        $bc->fill($card);
        $bc->save();
      }
    }
    $bc_order->save();
    return Redirect::to(action('BcOrdersController@index'))->withSuccess('Se ha guardado la orden satisfactoriamente');
  }

  public function destroy($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    if($bc_order){
      $bc_order->delete();
    }

    return Redirect::to(action('BusinessCardsController@index'))->withSuccess('Se ha cancelado la orden');
  }




  public function postReceive($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    $complete = 1;
    foreach(Input::get('card') as $id => $card){
      $pivot = $bc_order->businessCards()->where('id', $id)->first()->pivot;
      $complete *= $card['status'];
      $pivot->status = $card['status'];
      $pivot->comments = $card['comments'];
      $pivot->save();
    }
    if($complete){
      $bc_order->status = $complete;
    }else{
      $bc_order->status = 2;
    }
    $bc_order->receive_comments = Input::get('receive_comments');
    $bc_order->save();
    return Redirect::to(action('BusinessCardsController@index'))->withSuccess('Se ha actualizado la información');
  }
}
