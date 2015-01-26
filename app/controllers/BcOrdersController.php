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

    $date = \Carbon\Carbon::now();
    $date->day = 1;
    $date->subMonth();

    // SELECT SUM(quantity) FROM bc_order_business_card LEFT JOIN business_cards ON bc_order_business_card.business_card_id = business_cards.id LEFT JOIN bc_orders on bc_orders.id = bc_order_business_card.bc_order_id WHERE
    // bc_orders.user_id = 2 AND business_cards.nombre_puesto LIKE '%Gerencia%';

    $director_requested = DB::table('bc_order_business_card')->select(DB::raw('SUM(quantity) as quantity'))
      ->leftJoin('business_cards', 'bc_order_business_card.business_card_id', '=', 'business_cards.id')
      ->leftJoin('bc_orders', 'bc_orders.id', '=', 'bc_order_business_card.bc_order_id')
      ->where(DB::raw('bc_orders.user_id'), Auth::id())
      ->where(DB::raw('business_cards.nombre_puesto'), 'LIKE', '%Director%')
      ->where(DB::raw('bc_orders.updated_at'), '>=', $date->toDateString())->first()->quantity;
    $manager_requested = DB::table('bc_order_business_card')->select(DB::raw('SUM(quantity) as quantity'))
      ->leftJoin('business_cards', 'bc_order_business_card.business_card_id', '=', 'business_cards.id')
      ->leftJoin('bc_orders', 'bc_orders.id', '=', 'bc_order_business_card.bc_order_id')
      ->where(DB::raw('bc_orders.user_id'), Auth::id())
      ->where(DB::raw('business_cards.nombre_puesto'), 'LIKE', '%Gerente%')
      ->where(DB::raw('bc_orders.updated_at'), '>=', $date->toDateString())->first()->quantity;

    $date->subMonth();

    // $managers_requested = Auth::user()->bcOrders()->whereHas('businessCards', function($q){
    //   $q->where('nombre_puesto', 'LIKE', '%Gerente%');
    // })->where('updated_at', '>=', $date->toDateString())->select(DB::raw('bc_order_business_card.quantity as count'))->first()->count;
    $managers_requested = 0;

    $quantities = Input::get('quantities', []);
    foreach($cards as $card_id)
    {
      $card = BusinessCard::find($card_id);
      if(strpos($card->nombre_puesto, 'Director') !== FALSE and $director_requested >= 100){
        $bc_order->delete();
        return Redirect::to(URL::previous())->withInfo('No se pudo realizar su pedido porque solo puede pedir 100 tarjetas para director al mes');
      }elseif(strpos($card->nombre_puesto, 'Gerente') !== FALSE and $manager_requested >= 100){
        $bc_order->delete();
        return Redirect::to(URL::previous())->withInfo('No se pudo realizar su pedido porque solo puede pedir 100 tarjetas para gerente al mes');
      }
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
