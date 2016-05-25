<?php

class AddProductsController extends BaseController
{

  public function getIndex($order_id)
  {
  	$category_id = NULL;
  	$products = Product::select('*');

    if($category_id){
      $activeCategory = Category::find($category_id);
      if(!$activeCategory){
        return $this->index($category_id)->withErrors('No se encontró la categoría');
      }
      $products->where('category_id', $category_id);

    }

    if(Input::has('name')){
      $products->where('name', 'like', "%".Input::get('name')."%");
    }
  
   return View::make('orders.add_product')->with([
      'products' => $products->paginate(15),
      'categories' => Category::all(),
      'activeCategory' => @$activeCategory,
      ])->withOrderId($order_id);
  }



}
?>