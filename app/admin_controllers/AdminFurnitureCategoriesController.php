<?

class AdminFurnitureCategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::furniture_categories.index')->withCategories(FurnitureCategory::orderBy('name')->get());
  }

  public function create()
  {
    return View::make('admin::furniture_categories.create')->withCategory(new FurnitureCategory);
  }

  public function store()
  {
    $category = new FurnitureCategory(Input::all());
    if($category->save()){
      return Redirect::to(action('AdminFurnitureCategoriesController@index'))->withSuccess('Se ha creado la nueva categoría')->withCategory($category);  
        }else{
      return View::make('admin::furniture_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

  public function edit($category_id)
  {
    $category = FurnitureCategory::find($category_id);
    if(!$category){
      return Redirect::to(action('AdminFurnitureCategoriesController@index'))->withErrors('No se encontró la categoría');
    }else{
      return View::make('admin::furniture_categories.create')->withCategory($category);
    }
  }

  public function update($category_id)
  {
    $category = FurnitureCategory::find($category_id);
    if(!$category){
      return Redirect::to(action('AdminFurnitureCategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $category->fill(Input::all());
    if($category->save()){
      $response = Redirect::to(action('AdminFurnitureCategoriesController@index'))->withSuccess('Se ha actualizado la categoría');
      
      return $response;
    }else{
      return View::make('admin::furniture_categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }
}
