<?php

class AdminProductsController extends AdminBaseController
{

  public function index()
  {
    return View::make('admin::products.index')->withProducts(Product::withTrashed()->orderBy('category_id')->orderBy('name')->paginate(25));
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
        
      }
      if($product->save()){
        return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::products.create')->withProduct($product);
      }
    }
  }
  public function destroy($product_id){
    $product = Product::withTrashed()->find($product_id);
    if($product->trashed()){
      $product->restore();
      return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $product->delete();
      return Redirect::to(action('AdminProductsController@index'))->withInfo('Se ha inabilitado el producto');
    }
    
  }
}
