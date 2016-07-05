<?

class AdminTrainingProductsController extends AdminBaseController{

 public function index()
  {
    $active_tab = Session::get('active_tab', Input::get('active_tab',1));
    $categories = TrainingCategory::lists('name');
    if (Input::has('active_tab')) {
      $products = TrainingProduct::withTrashed()->where('training_category_id',Input::get('active_tab'));
    }else{
      $products = TrainingProduct::withTrashed()->where('training_category_id',1);
    }
       
    return View::make('admin::training_products.index')->withProducts($products->orderBy('training_category_id')->orderBy('name')->paginate(10))
                                              ->withCategories(TrainingCategory::all())
                                              ->withActiveTab($active_tab);                                          
  }

  public function create()
  {
    return View::make('admin::training_products.create')->withProduct(new TrainingProduct);
  }

  public function store()
  {
    $product = new TrainingProduct(Input::all());
    if($product->save()){
      return Redirect::to(action('AdminTrainingProductsController@index'))->withSuccess('Se ha creado el nuevo producto');
    }else{
      return View::make('admin::training_products.create')->withProduct($product);
    }
  }

  public function edit($product_id)
  {
    $product = TrainingProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminTrainingProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::training_products.create')->withProduct($product);
    }
  }

  public function update($product_id)
  {
    $product = TrainingProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminTrainingProductsController@index'))->withErrors('No se encontró el producto');
    }else{
      $product->fill(Input::all());
      if(Input::has('training_category_id') and Input::get('training_category_id')){
        $product->training_category_id = Input::get('training_category_id');
        
      }
      if($product->save()){
        return Redirect::to(action('AdminTrainingProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::training_products.create')->withProduct($product);
      }
    }
  }
  public function destroy($product_id){
    $product = TrainingProduct::withTrashed()->find($product_id);
    if($product->trashed()){
      $product->restore();
      return Redirect::to(action('AdminTrainingProductsController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $product->delete();
      return Redirect::to(action('AdminTrainingProductsController@index'))->withInfo('Se ha inabilitado el producto');
    }
    
  }
}
