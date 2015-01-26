<?

class AdminBcOrdersController extends AdminBaseController{

  public function index()
  {
    return View::make('admin::bc_orders.index')->withBcOrders(BcOrder::all());
  }

  public function show($bc_order_id)
  {
    return View::make('admin::bc_orders.show')->withBcOrder(BcOrder::find($bc_order_id));
  }
}
