<?

class AdminBcOrdersController extends AdminBaseController{

  public function index()
  {
    return View::make('admin::bc_orders.index')->withBcOrders(BcOrder::all());
  }

  public function show($bc_order_id)
  {
  	$blank_card = DB::table('blank_cards_bc_order')->where('bc_order_id', $bc_order_id)->first();
    return View::make('admin::bc_orders.show')->withBcOrder(BcOrder::find($bc_order_id))->withBlankCard($blank_card);
  }


  public function destroy($bc_order_id)
  {
    $bc_order = BcOrder::find($bc_order_id);
    if(!$bc_order){
      return Redirect::to('/')->withWarning('No se encontró la orden');
    }

    $bc_order = $bc_order->delete();
    return Redirect::to(action('AdminBcOrdersController@index'))->withSuccess('Se ha eliminado la orden');
  }
}
