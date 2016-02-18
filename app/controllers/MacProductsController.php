<?php

class MacProductsController extends \BaseController {

	public function index($category_id = NULL)
	{

	    $products = MacProduct::select('*');

	    if($category_id){
	      $activeCategory = MacCategory::find($category_id);
	      if(!$activeCategory){
	        return $this->index()->withErrors('No se encontró la categoría');
	      }
	      $products->where('category_id', $category_id);

	    }

	    if(Input::has('name')){
	      $products->where('name', 'like', "%".Input::get('name')."%");
	    }

	    return View::make('mac_products.index')->with([
	      'products' => $products->paginate(15),
	      'categories' => MacCategory::all(),
	      'activeCategory' => @$activeCategory,
	      ]);
	}

}
