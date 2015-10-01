<?

class AdminFurnitureSubcategoriesController extends BaseController{

  public function index()
  {
    return View::make('admin::furniture_subcategories.index')->withSubategories(FurnitureSubcategory::orderBy('name')->get());
  }

  public function create()
  {
    Log::info(Input::all());

    $category = FurnitureCategory::find(Input::get('furniture_category_id'));

    Log::debug($category);
    return View::make('admin::furniture_subcategories.create')->withSubcategory(new FurnitureSubcategory)->withCategory($category);
  }

  public function store()
  {
    Log::info(Input::all());
    $subcategory = new FurnitureSubcategory(Input::all());
    $categories = FurnitureCategory::all();
    $subcategory->furniture_category_id = Input::get('furniture_category_id');
    if($subcategory->save()){
      return View::make('admin::furniture_categories.index')->withCategories($categories)->withSuccess('Se añadio la subcategoria');
    }else{
      return View::make('admin::furniture_categories.index')->withCategories($categories)->withErrors($subcategory->getErrors());
    }
  }

  public function edit($subcategory_id)
  {
    
    $subcategory = FurnitureSubcategory::find($subcategory_id);

    if(!$subcategory){
      return View::make('admin::furniture_subcategories.create')->withErrors('No se encontro la categoria');
    }else{

      return View::make('admin::furniture_subcategories.create')->withSubcategory($subcategory)->withCategory($subcategory->furniture_category);
    }
  }

  public function update($subcategory_id)
  {
    $subcategory = FurnitureSubcategory::find($subcategory_id);
    $categories = FurnitureCategory::all();

    if(!$subcategory){
    
      return Redirect::to(action('AdminFurnitureSubcategoriesController@index'))->withErrors('No se encontró la categoría');
    }
    $subcategory->fill(Input::all());
    
    if($subcategory->save()){
 
      return View::make('admin::furniture_categories.index')->withCategories($categories)->withSuccess('Se actualizado la subcategoria');
    }else{
    
      return View::make('admin::furniture_subcategories.create')->withSubcategory($subcategory)->withErrors($subcategory->getErrors());
    }
  }

  public function destroy($subcategory_id){
    $categories = FurnitureCategory::all(); 
    $furniture_subcategory = FurnitureSubcategory::find($subcategory_id);
    $furniture_subcategory->destroy();

    return View::make('admin::furniture_categories.index')->withCategories($categories)->withSuccess('Se eliminado la subcategoria');

  }
}