<?

class AdminReportsController extends AdminBaseController{

  public function getOrdersReport()
  {
    return View::make('admin::reports.orders');
  }

  public function getBcOrdersReport()
  {
    return View::make('admin::reports.bc_orders');
  }

}
