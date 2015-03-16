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
      $blank_card = DB::table('blank_cards_bc_order')->where('bc_order_id', $bc_order_id)->first();
      return View::make('bc_orders.show')->withBcOrder($bc_order)->withBlankCard($blank_card);
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
    $remaining_cards = 200 - Auth::user()->bcOrders()
      ->leftJoin('blank_cards_bc_order', 'blank_cards_bc_order.bc_order_id', '=','bc_orders.id')
      ->select(DB::raw('SUM(quantity) as blank'))
      ->where(DB::raw('MONTH(bc_orders.updated_at)'), DB::raw('MONTH(NOW())'))
      ->where(DB::raw('YEAR(bc_orders.updated_at)'), DB::raw('YEAR(NOW())'))
      ->first()->blank;

    return View::make('bc_orders.edit')->withBcOrder($bc_order)->withRemainingCards($remaining_cards);
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

    if(Input::has('blank_cards') and Input::get('blank_cards') > 0){
      DB::table('blank_cards_bc_order')->insert([
        'quantity' => Input::get('blank_cards')*100,
        'bc_order_id' => $bc_order->id,
        ]);
    }

    if(Input::get('talento_nombre') or Input::get('gerente_nombre')){
      $extras = new BcOrdersExtras;
    if(Input::get('talento_nombre')){
      $extras->fill(array(
        "talento_nombre" => Input::get('talento_nombre'),
        "talento_direccion" => Input::get('talento_direccion'),
        "talento_direccion_alternativa" => Input::get('talento_direccion_alternativa'),
        "talento_tel" => Input::get('talento_tel'),
        "talento_cel" => Input::get('talento_cel'),
        "talento_email" => Input::get('talento_email')
        ));
    }
         if(Input::get('gerente_nombre')){
        $extras->fill(array(
        "gerente_nombre" => Input::get('gerente_nombre'),
        "gerente_direccion" => Input::get('gerente_direccion'),
        "gerente_direccion_alternativa" => Input::get('gerente_direccion_alternativa'),
        "gerente_tel" => Input::get('gerente_tel'),
        "gerente_cel" => Input::get('gerente_cel'),
        "gerente_email" => Input::get('gerente_email')
        ));
        
        }
        
        $extras->bcOrder()->associate($bc_order);  
        $extras->save();  
    }
    return Redirect::to(action('BcOrdersController@index'))->withSuccess('Se ha guardado la orden satisfactoriamente');
  }

    public function destroy($order_id)
    {
    $order = BcOrder::find($order_id);
    if(!$order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }
    if($order->status == 0) 
    {
      $order = $order->delete();
      return Redirect::to(action('BcOrdersController@index'))->withSuccess('Se ha eliminado la orden');  
    }else{
    
      return Redirect::back()->withErrors('El pedido ha sido aprobado no se puede eliminar');  

      }
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

    if(Input::has('blank_cards_status')){
      $complete *= Input::get('blank_cards_status');
      DB::table('blank_cards_bc_order')->where('bc_order_id', $bc_order_id)
        ->update([
          'status' => Input::get('blank_cards_status'),
          'comments' => Input::get('blank_cards_comments'),
          ]);

    }

    


 
    


    if($bc_order->extra){

      $bc_order->extra->gerente_comentarios = Input::get('gerente_comentarios');
      $bc_order->extra->talento_comentarios = Input::get('talento_comentarios');
      $bc_order->extra->gerente_estatus = Input::get('gerente_estatus');
      $bc_order->extra->talento_estatus = Input::get('talento_estatus');
      $bc_order->extra->save();  
      $complete *= Input::get('gerente_estatus');
      $complete *= Input::get('talento_estatus');
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
