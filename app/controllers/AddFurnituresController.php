<?php

class AddFurnituresController extends BaseController
{

  public function getIndex($order_id)
  {
  	$category_id = NULL;
  	$furnitures = Furniture::select('*');

    if($category_id){
      $activeCategory = FurnitureCategory::find($category_id);
      if(!$activeCategory){
        return $this->index($category_id)->withErrors('No se encontró la categoría');
      }
      $furnitures->where('category_id', $category_id);

    }

    if(Input::has('name')){
      $furnitures->where('name', 'like', "%".Input::get('name')."%");
    }
  
   return View::make('orders_furnitures.add_product')->with([
      'furnitures' => $furnitures->paginate(15),
      'categories' => FurnitureCategory::all(),
      'activeCategory' => @$activeCategory,
      ])->withOrderId($order_id);
  }



}
?>