<?php

class AdminProductsGeneralController extends AdminBaseController{  


  public function getIndex()
  {
    return View::make('admin::general_products.index');
  }

}
?>