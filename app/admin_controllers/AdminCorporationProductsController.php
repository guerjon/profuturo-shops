<?

class AdminCorporationProductsController extends AdminBaseController{

 public function index()
  {
    $active_tab = Session::get('active_tab', Input::get('active_tab',1));
    $categories = CorporationCategory::lists('name');
    if (Input::has('active_tab')) {
      $products = CorporationProduct::withTrashed()->where('corporation_category_id',Input::get('active_tab'));
    }else{
      $products = CorporationProduct::withTrashed()->where('corporation_category_id',1);
    }
       
    if (Input::has('excel')) {
      $headers = 
        [
          'NOMBRE',
          'MODELO',
          'DESCRIPCIÓN',
          'MAXIMO',
          'UNIDAD DE MEDIDA',
          'ID PEOPLE','MBA_CODE',
          'PRECIO',
          'SKU',
          'CATEGORIA',
          'FECHA DE CARGA'
        ];
        
      $datetime = \Carbon\Carbon::now()->format('d-m-Y');
      Excel::create('PRODUCTOS_PAPELERIA_CORPORATIVO_'.$datetime, function($excel) use($products,$headers){
        $excel->sheet('productos',function($sheet)use($products,$headers){
        $sheet->appendRow($headers);
        $products = $products->get(); 

        foreach ($products as $product) {
          
          $sheet->appendRow([
            $product->name,
            $product->model,
            $product->description,
            $product->max_stock,
            $product->measure_unit,
            $product->id_people,
            $product->mba_code,
            $product->price,
            $product->sku,
            Lang::get('corporation_categories.'.$product->corporation_category_id),
            $product->updated_at->format('d-m-Y')
          ]); 
        }
        });
      })->download('xlsx');        
    }else{
      return View::make('admin::corporation_products.index')->withProducts($products->orderBy('corporation_category_id')->orderBy('name')->paginate(10))
                                                ->withCategories(CorporationCategory::all())
                                                ->withActiveTab($active_tab);      
    }

                                          
  }

  public function create()
  {
    return View::make('admin::corporation_products.create')->withProduct(new CorporationProduct);
  }

  public function store()
  {
    $product = new CorporationProduct(Input::all());
    if($product->save()){
      return Redirect::to(action('AdminCorporationProductsController@index'))->withSuccess('Se ha creado el nuevo producto');
    }else{
      return View::make('admin::corporation_products.create')->withProduct($product);
    }
  }

  public function edit($product_id)
  {
    $product = CorporationProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminCorporationProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::corporation_products.create')->withProduct($product);
    }
  }

  public function update($product_id)
  {
    $product = CorporationProduct::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminCorporationProductsController@index'))->withErrors('No se encontró el producto');
    }else{
      $product->fill(Input::all());
      if(Input::has('corporation_category_id') and Input::get('corporation_category_id')){
        $product->corporation_category_id = Input::get('corporation_category_id');
        
      }
      if($product->save()){
        return Redirect::to(action('AdminCorporationProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::corporation_products.create')->withProduct($product);
      }
    }
  }
  public function destroy($product_id){
    $product = CorporationProduct::withTrashed()->find($product_id);
    if($product->trashed()){
      $product->restore();
      return Redirect::to(action('AdminCorporationProductsController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $product->delete();
      return Redirect::to(action('AdminCorporationProductsController@index'))->withInfo('Se ha inabilitado el producto');
    }
    
  }
}
