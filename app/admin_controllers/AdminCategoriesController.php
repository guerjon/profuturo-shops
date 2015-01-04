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
      $ids = Input::get('subcategory_ids', []);
      $names = Input::get('subcategory_names', []);
      if(count($ids) !== count($names)){
        return $response->withErrors('No se actualizaron las categorías por error 401: IDS and Names not match');
      }

      if(count($ids) > 0){
        $toDelete = $category->subcategories()->whereNotIn('id', $ids);
      }else{
        $toDelete = $category->subcategories();
      }
      $toDelete->delete();

      
      $errors = [];
      for($i = 0; $i<count($ids); $i++){
        $id = $ids[$i];
        $name = $names[$i];
        if($id){
          $subcategory = Subcategory::find($id);
          if(!$subcategory){
            $errors[] = "No se pudo actualizar la categoría $id porque fue eliminada de la base de datos.";
            continue;
          }

        }else{
          $subcategory = new Subcategory;
          $subcategory->category_id =$category_id;
        }
        $subcategory->name = $name;
        Log::info('Saving', $subcategory->toArray());
        if($subcategory->save()){

        }else{
          Log::info('Not saved');
        }
      }
      if(count($errors > 0)){
        $response->withErrors($errors);
      }

      return $response;
    }else{
      return View::make('admin::categories.create')->withCategory($category)->withErrors($category->getErrors());
    }
  }
}
