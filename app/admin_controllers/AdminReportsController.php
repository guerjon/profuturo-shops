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
    $management = User::where('role','!=','admin')->lists('gerencia');
    $regions = Region::lists('name','id'); 
    $divisionals = Divisional::lists('name','id');
    return View::make('admin::reports.bc_orders')->withManagement($management)->withRegions($regions)->withDivisionals($divisionals);
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

  public function getGeneralRequestReport()
  {
    return View::make('admin::reports.general_request');
  }

  public function getFurnituresOrdersReport()
  {
    $categories = Category::lists('name','id');
    $management = User::where('role','!=','admin')->lists('gerencia','id');
    $business_line = User::distinct()->where('role','!=','admin')->lists('linea_negocio','linea_negocio');
    return View::make('admin::reports.furnitures')->withCategories($categories)->withGerencia($management)->withBusinessLine($business_line);
  }

  public function getBIReport(){
    $users = User::all();
    $categories = Category::all()->lists('name','id');
    $products = Product::all()->lists('name','id');

    return View::make('admin::reports.bi')
    ->withUsers($users)
    ->withCategories($categories)
    ->withProducts($products);

  }

  public function postCreatePdf()
  {

    $pdf = App::make('dompdf');
    $pdf->loadHTML(Input::get('htmlContent'));
    $pdf->stream();
    return Redirect::action('AdminReportsController@getIndex');
  }
  


}
