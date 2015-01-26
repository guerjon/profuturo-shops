<?

class BusinessCardsController extends BaseController{

  public function index()
  {
    return View::make('business_cards.index')->withCards(Auth::user()->business_cards);
  }
}
