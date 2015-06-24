<?

class AdminReportsController extends AdminBaseController{

  

  public function getIndex()
  {
    $categories = Category::lists('name','id');
    
    return View::make('admin::reports.index')->withCategories($categories);
  }
  
  public function getOrdersReport()
  {
    $categories = Category::lists('name','id');
    $management = User::where('role','!=','admin')->lists('gerencia','id');
    $business_line = User::distinct()->where('role','!=','admin')->lists('linea_negocio','linea_negocio');
    
    return View::make('admin::reports.orders')->withCategories($categories)->withGerencia($management)->withBusinessLine($business_line);
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
  { $categories = Category::lists('name','id');
    return View::make('admin::reports.product_orders')->withCategories($categories);
  }

   public function getActiveUserOrdersReport()
  {
    return View::make('admin::reports.active_user_orders');
  }

  public function getTotalUserOrdersReport(){
    return View::make('admin::reports.total_user_orders');
  }



}
