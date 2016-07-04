<?php
class AdminTrainingCategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::corporation_categories.index')->withCategories(CorporationCategory::orderBy('name')->get());
  }

  public function create()
  {
    return View::make('admin::corporation_categories.create')->withCategory(new CorporationCategory);
  }

  public function store()
  {
    $category = new CorporationCategory(Input::all());
    if($category->save()){
      return Redirect::to(action('AdminCorporationCategoriesController@index'))->withSuccess('Se ha creado la nueva categoría');
    }else{
      return View::make('admin::corporation_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

  public function edit($corporation_category_id)
  {
    $category = CorporationCategory::find($corporation_category_id);

    if(!$category){
      return Redirect::to(action('AdminCorporationCategoriesController@index'))->withErrors('No se encontró la categoría');
    }else{
      return View::make('admin::corporation_categories.create')->withCategory($category);
    }
  }

  public function update($corporation_category_id)
  {
    $category = CorporationCategory::find($corporation_category_id);
    if(!$category){
      return Redirect::to(action('AdminCorporationCategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $category->fill(Input::all());
    if($category->save()){
      $response = Redirect::to(action('AdminCorporationCategoriesController@index'))->withSuccess('Se ha actualizado la categoría');
      
      return $response;
    }else{
      return View::make('admin::corporation_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

}