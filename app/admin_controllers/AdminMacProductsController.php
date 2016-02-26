<?

class AdminMacProductsController extends AdminBaseController{

 public function index()
  {
    $active_tab = Session::get('active_tab', Input::get('active_tab',1));
    $categories = MacCategory::lists('name');
    if (Input::has('active_tab')) {
      $products = MacProduct::withTrashed()->where('category_id',Input::get('active_tab'));
    }else{
      $products = MacProduct::withTrashed()->where('category_id',1);
    }
       
    return View::make('admin::mac_products.index')->withProducts($products->orderBy('category_id')->orderBy('name')->paginate(10))
                                              ->withCategories(MacCategory::all())
                                              ->withActiveTab($active_tab);                                          
  }

  public function create()
  {
    return View::make('admin::mac_products.create')->withProduct(new MacProduct);
  }

  public function store()
  {
    $product = new MacProduct(Input::all());
    if(Input::has('category_id') and Input::get('category_id')){
      $product->category_id = Input::get('category_id');
    }

    if($product->save()){
      return Redirect::to(action('AdminMacProductsController@index'))->withSuccess('Se ha creado el nuevo producto');
    }else{
      return View::make('admin::mac_products.create')->withProduct($product);
    }
  }

  public function edit($product_id)
  {
    $product = MacProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminMacProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::mac_products.create')->withProduct($product);
    }
  }

  public function update($product_id)
  {
    $product = MacProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminMacProductsController@index'))->withErrors('No se encontró el producto');
    }else{
      $product->fill(Input::all());
      if(Input::has('category_id') and Input::get('category_id')){
        $product->category_id = Input::get('category_id');
        
      }
      if($product->save()){
        return Redirect::to(action('AdminMacProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::mac_products.create')->withProduct($product);
      }
    }
  }
  public function destroy($product_id){
    $product = MacProduct::withTrashed()->find($product_id);
    if($product->trashed()){
      $product->restore();
      return Redirect::to(action('AdminMacProductsController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $product->delete();
      return Redirect::to(action('AdminMacProductsController@index'))->withInfo('Se ha inabilitado el producto');
    }
    
  }
}
