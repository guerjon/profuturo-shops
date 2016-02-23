<?php
class AdminMacCategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::mac_categories.index')->withCategories(MacCategory::orderBy('name')->get());
  }

  public function create()
  {
    return View::make('admin::mac_categories.create')->withCategory(new MacCategory);
  }

  public function store()
  {
    $category = new MacCategory(Input::all());
    if($category->save()){
      return Redirect::to(action('AdminMacCategoriesController@index'))->withSuccess('Se ha creado la nueva categoría');
    }else{
      return View::make('admin::mac_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

  public function edit($mac_category_id)
  {
    $category = MacCategory::find($mac_category_id);

    if(!$category){
      return Redirect::to(action('AdminMacCategoriesController@index'))->withErrors('No se encontró la categoría');
    }else{
      return View::make('admin::mac_categories.create')->withCategory($category);
    }
  }

  public function update($mac_category_id)
  {
    $category = MacCategory::find($mac_category_id);
    if(!$category){
      return Redirect::to(action('AdminMacCategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $category->fill(Input::all());
    if($category->save()){
      $response = Redirect::to(action('AdminMacCategoriesController@index'))->withSuccess('Se ha actualizado la categoría');
      
      return $response;
    }else{
      return View::make('admin::mac_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

}