<?

class CorporationBusinessCardsController extends BaseController{

  private function sumDay($date){
    return \Carbon\Carbon::createFromFormat('Y-m-d',$date)->addDay()->format('Y-m-d');
  } 

  public function index()
  {

  	$tarjetasProhibidas = DB::table('bc_orders')
    ->join('bc_order_business_card','bc_orders.id','=','bc_order_business_card.bc_order_id')
  	->join('business_cards','business_cards.id','=','bc_order_business_card.business_card_id')
  	->select('bc_orders.created_at', 'business_cards.id')
    ->whereNull('bc_orders.deleted_at')
  	->where(DB::raw('datediff(NOW(),bc_orders.created_at)'),'<','31')
  	->where('user_id','=',Auth::id())
    ->where('business_cards.type','=','corporation')
    ->lists('bc_orders.created_at', 'id');

    $access = false;
    $divisional_id = Auth::user()->divisional ? Auth::user()->divisional->id : 0;

    $dates = DB::table('divisionals_users')
      ->where('divisional_id',$divisional_id)
      ->where('from','<=',\Carbon\Carbon::now()->format('Y-m-d'))
      ->where('until','>=',\Carbon\Carbon::now()->format('Y-m-d'));
  
    $divisional = DB::table('divisionals_users')
      ->where('divisionals_users.divisional_id',Auth::user()->divisional_id)
      ->orderBy('created_at','desc')
      ->first();

    if($divisional){
      $last_order = DB::table('users')
          ->join('divisionals_users','divisionals_users.divisional_id','=','users.divisional_id')
          ->join('bc_orders','bc_orders.user_id','=','users.id')
          ->where('users.id',Auth::user()->id)
          ->where('bc_orders.created_at','>=',$divisional->from)
          ->where('bc_orders.created_at','<=',$this->sumDay($divisional->until))
          ->whereNull('bc_orders.deleted_at'); 
    }else{
      $last_order = 1;      
    }

    
    $access = ($dates->count() > 0) ? ($last_order->count() < 1) : false;
    
    $access = true;
    $cards = Auth::user()->businessCards()->orderBy('no_emp');
  
    return View::make('corporation_business_cards.index')
      ->withCards($cards->get())
      ->withForbidden($tarjetasProhibidas)
      ->withForbid(strpos(Auth::user()->gerencia, 'CUENTAS') === FALSE)
      ->withAccess($access);
  }
}

