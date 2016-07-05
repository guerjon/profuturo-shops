<?php

class TrainingProductsController extends \BaseController {

	public function index($category_id = NULL)
	{

	    $products = TrainingProduct::where('id','>=','1');
	    
	    if($category_id){
	      $activeCategory = TrainingCategory::find($category_id);
	      if(!$activeCategory){
	        return $this->index()->withErrors('No se encontró la categoría');
	      }

	      $products->where('training_category_id','=',$category_id);
	    }


	    if(Input::has('name')){
	      $products->where('name', 'like', "%".Input::get('name')."%");
	    }
		    	
	    return View::make('training_products.index')->with([
	      'products' => $products->paginate(15),
	      'categories' => TrainingCategory::all(),
	      'activeCategory' => @$activeCategory,
	      ]);
	}

}
