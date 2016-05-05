<?php

class CorporationProductsController extends \BaseController {

	public function index($category_id = NULL)
	{

	    $products = CorporationProduct::where('id','>','1');
	    
	    if($category_id){
	      $activeCategory = CorporationCategory::find($category_id);
	      if(!$activeCategory){
	        return $this->index()->withErrors('No se encontró la categoría');
	      }

	      $products->where('corporation_category_id','=',$category_id);
	    }


	    if(Input::has('name')){
	      $products->where('name', 'like', "%".Input::get('name')."%");
	    }
		    	
	    return View::make('corporation_products.index')->with([
	      'products' => $products->paginate(15),
	      'categories' => CorporationCategory::all(),
	      'activeCategory' => @$activeCategory,
	      ]);
	}

}
