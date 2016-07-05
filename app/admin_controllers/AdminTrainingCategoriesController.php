<?php
class AdminTrainingCategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::training_categories.index')->withCategories(TrainingCategory::orderBy('name')->get());
  }

  public function create()
  {
    return View::make('admin::training_categories.create')->withCategory(new TrainingCategory);
  }

  public function store()
  {
    $category = new TrainingCategory(Input::all());
    if($category->save()){
      return Redirect::to(action('AdminTrainingCategoriesController@index'))->withSuccess('Se ha creado la nueva categoría');
    }else{
      return View::make('admin::training_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

  public function edit($training_category_id)
  {
    $category = TrainingCategory::find($training_category_id);

    if(!$category){
      return Redirect::to(action('AdminTrainingCategoriesController@index'))->withErrors('No se encontró la categoría');
    }else{
      return View::make('admin::training_categories.create')->withCategory($category);
    }
  }

  public function update($training_category_id)
  {
    $category = TrainingCategory::find($training_category_id);
    if(!$category){
      return Redirect::to(action('AdminTrainingCategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $category->fill(Input::all());
    if($category->save()){
      $response = Redirect::to(action('AdminTrainingCategoriesController@index'))->withSuccess('Se ha actualizado la categoría');
      
      return $response;
    }else{
      return View::make('admin::training_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

}