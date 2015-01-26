<?php

class AdminProductsController extends AdminBaseController
{

  public function index()
  {
    return View::make('admin::products.index')->withProducts(Product::orderBy('category_id')->orderBy('name')->paginate(25));
  }

  public function create()
  {
    return View::make('admin::products.create')->withProduct(new Product);
  }

  public function store()
  {
    $product = new Product(Input::all());
    if(Input::has('category_id') and Input::get('category_id')){
      $product->category_id = Input::get('category_id');
    }

    if($product->save()){
      return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha creado el nuevo producto');
    }else{
      return View::make('admin::products.create')->withProduct($product);
    }
  }

  public function edit($product_id)
  {
    $product = Product::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::products.create')->withProduct($product);
    }
  }

  public function update($product_id)
  {
    $product = Product::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminProductsController@index'))->withErrors('No se encontró el producto');
    }else{
      $product->fill(Input::all());
      if(Input::has('category_id') and Input::get('category_id')){
        $product->category_id = Input::get('category_id');
        if(Input::has('subcategory_id') and Input::get('subcategory_id')){
          $product->subcategory_id = Input::get('subcategory_id');
        }else{
          $product->subcategory_id = NULL;
        }
      }
      if($product->save()){
        return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::products.create')->withProduct($product);
      }
    }
  }
}
