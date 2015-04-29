<?

class AdminReportsController extends AdminBaseController{

  public function getOrdersReport()
  {

  	//$category = DB::table('categories')->select('name')->orderBy('name');
 	//->withCategories($category)
    return View::make('admin::reports.orders');
  	
  }

  public function getBcOrdersReport()
  {
    return View::make('admin::reports.bc_orders');
  }

  public function getUserOrdersReport()
  {
    return View::make('admin::reports.user_orders');
  }

  public function getProductOrdersReport()
  {
        return View::make('admin::reports.product_orders');
  }

   public function getActiveUserOrdersReport()
  {
    return View::make('admin::reports.active_user_orders');
  }
}
