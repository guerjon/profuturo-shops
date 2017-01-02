<?php

class AdminProductsController extends AdminBaseController
{

  public function index()
  {
    $active_tab = Session::get('active_tab', Input::get('active_tab',1));
    $categories = Category::lists('name');
    if (Input::has('active_tab')) {
      $products = Product::where('category_id',Input::get('active_tab'));
    }else{
      $products = Product::where('category_id',1);
    }
   
    if(Input::has('excel')){
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
          'CATEGORIA'
        ];
        
      $datetime = \Carbon\Carbon::now()->format('d-m-Y');
      
      Excel::create('PRODUCTOS_PAPELERIA_'.$datetime, function($excel) use($products,$headers){
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
            Lang::get('paper_categories.'.$product->category_id) 
          ]); 
        }
        });
      })->download('xlsx');

    }else{
      return View::make('admin::products.index')
        ->withProducts($products->orderBy('category_id')->orderBy('name')->paginate(10))
        ->withCategories(Category::all())
        ->withActiveTab($active_tab);
    }                                       
  }

  public function create()
  {
    return View::make('admin::products.create')->withProduct(new Product);
  }

  public function store()
  {
    $product = new Product(Input::all());
    if(Input::has('category_id') and Input::get('category_id')){
      $product->category_id = Input::get('category_id');
    }

    if($product->save()){
      return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha creado el nuevo producto');
    }else{
      return View::make('admin::products.create')->withProduct($product);
    }
  }

  public function edit($product_id)
  {
    $product = Product::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::products.create')->withProduct($product);
    }
  }

  public function update($product_id)
  {
    $product = Product::find($product_id);
    if(!$product){
      return Redirect::to(action('AdminProductsController@index'))->withErrors('No se encontró el producto');
    }else{
      $product->fill(Input::all());
      if(Input::has('category_id') and Input::get('category_id')){
        $product->category_id = Input::get('category_id');
        
      }
      if($product->save()){
        return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha actualizado el producto');
      }else{
        return View::make('admin::products.create')->withProduct($product);
      }
    }
  }
  public function destroy($product_id){
    $product = Product::withTrashed()->find($product_id);
    if($product->trashed()){
      $product->restore();
      return Redirect::to(action('AdminProductsController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $product->delete();
      return Redirect::to(action('AdminProductsController@index'))->withInfo('Se ha inabilitado el producto');
    }
    
  }
}
