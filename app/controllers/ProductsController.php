<?

class ProductsController extends BaseController
{

  public function index($category_id = NULL)
  {

    $products = Product::select('*');

    if($category_id){
      $activeCategory = Category::find($category_id);
      if(!$activeCategory){
        return $this->index()->withErrors('No se encontró la categoría');
      }
      $products->where('category_id', $category_id);

    }

    if(Input::has('name')){
      $products->where('name', 'like', "%".Input::get('name')."%");
    }
    // if(Input::has('category_id') and Input::get('category_id') !== NULL){
    //   if(Input::get('category_id') == 0){
    //     $products->whereNull('category_id');
    //   }else{
    //     $products->where('category_id', Input::get('category_id'));
    //   }
    // }
    return View::make('products.index')->with([
      'products' => $products->paginate(15),
      'categories' => Category::all(),
      'activeCategory' => @$activeCategory,
      ]);
  }

}
