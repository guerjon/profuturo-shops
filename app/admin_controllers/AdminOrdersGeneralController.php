<?

class AdminOrdersGeneralController extends AdminBaseController{  

  public function getIndex()
  {
    return View::make('admin::general_orders.index');
  }
  
}
