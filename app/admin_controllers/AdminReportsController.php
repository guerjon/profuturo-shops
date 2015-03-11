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

}
