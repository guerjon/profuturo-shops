<?

class AdminCategoriesGeneralController extends AdminBaseController{  

  public function getIndex()
  {
    return View::make('admin::general_categories.index');
  }
  
}
