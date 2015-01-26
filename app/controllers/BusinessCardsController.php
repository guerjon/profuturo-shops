<?

class BusinessCardsController extends BaseController{

  public function index()
  {
    return View::make('business_cards.index')->withCards(Auth::user()->businessCards()->where(DB::raw('DATEDIFF(NOW(), fecha_ingreso)'), '>=', '90')->get());
  }
}
