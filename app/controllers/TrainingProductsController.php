<?php

class TrainingProductsController extends \BaseController {

	public function index()
	{

	    $products = TrainingProduct::query();
	    $category = Input::get('category_id');

	    if($category != 'all')
	    	$products->where('training_category_id',$category);
	    
	    if(Input::has('name')){
	      $products->where('name','like',"%".Input::get('name')."%");
	    }

	    return View::make('training_products.index')->with([
	      'products' => $products->paginate(15),
	      'activeCategory' => $category,
	      ]);
	}

}
