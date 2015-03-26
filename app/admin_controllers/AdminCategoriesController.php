<?

class AdminCategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::categories.index')->withCategories(Category::orderBy('name')->get());
  }

  public function create()
  {
    return View::make('admin::categories.create')->withCategory(new Category);
  }

  public function store()
  {
    $category = new Category(Input::all());
    if($category->save()){
      return Redirect::to(action('AdminCategoriesController@index'))->withSuccess('Se ha creado la nueva categoría');
    }else{
      return View::make('admin::categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }

  public function edit($category_id)
  {
    $category = Category::find($category_id);
    if(!$category){
      return Redirect::to(action('AdminCategoriesController@index'))->withErrors('No se encontró la categoría');
    }else{
      return View::make('admin::categories.create')->withCategory($category);
    }
  }

  public function update($category_id)
  {
    $category = Category::find($category_id);
    if(!$category){
      return Redirect::to(action('AdminCategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $category->fill(Input::all());
    if($category->save()){
      $response = Redirect::to(action('AdminCategoriesController@index'))->withSuccess('Se ha actualizado la categoría');
      
      return $response;
    }else{
      return View::make('admin::categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }
}
