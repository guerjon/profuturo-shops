<?

class FurnituresController extends BaseController
{

  public function index($category_id = NULL)
  {

    $furnitures = Furniture::select('*');

  

    if(Input::has('name')){
      $furnitures->where('name', 'like', "%".Input::get('name')."%");
    }
    // if(Input::has('category_id') and Input::get('category_id') !== NULL){
    //   if(Input::get('category_id') == 0){
    //     $furnitures->whereNull('category_id');
    //   }else{
    //     $furnitures->where('category_id', Input::get('category_id'));
    //   }
    // }
    return View::make('furnitures.index')->with([
      'furnitures' => $furnitures->paginate(15),
      'categories' => Category::all(),
      'activeCategory' => @$activeCategory,
      ]);
  }

}
