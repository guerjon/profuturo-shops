<?

class BusinessCardsController extends BaseController{

  public function index()
  {

  	$tarjetasProhibidas = DB::table('bc_orders')
  	->join('bc_order_business_card','bc_orders.id','=','bc_order_business_card.bc_order_id')
  	->join('business_cards','business_cards.id','=','bc_order_business_card.business_card_id')
  	->select('business_cards.id')
    ->whereNull('bc_orders.deleted_at')
  	->where(DB::raw('datediff(NOW(),bc_orders.created_at)'),'<','31')
  	->where('user_id','=',Auth::id())->lists('bc_orders.created_at', 'id');

  $cards = Auth::user()->businessCards()->orderBy('no_emp');

  // if(strpos(Auth::user()->gerencia, 'CUENTAS') === FALSE){
  //   $cards->where(DB::raw('DATEDIFF(NOW(), fecha_ingreso)'), '>=', '90')
  //     ->whereNotIn('id',$tarjetasProhibidas);
  // }

   return View::make('business_cards.index')
      ->withCards($cards->get())
      ->withForbidden($tarjetasProhibidas)
      ->withForbid(strpos(Auth::user()->gerencia, 'CUENTAS') === FALSE);
  }
}
/*


     return View::make('business_cards.index')
    ->withCards(Auth::user()
    ->businessCards()
    ->where(DB::raw('DATEDIFF(NOW(), fecha_ingreso)'), '>=', '90')
    ->where()->get());
*/
