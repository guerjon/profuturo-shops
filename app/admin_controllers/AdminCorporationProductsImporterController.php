<?php

class AdminCorporationProductsImporterController  extends AdminBaseController {


   public function index()
  {	$categories  = CorporationCategory::all();
    return View::make('admin::corporation_products.index')
    	->withCorporationProducts(CorporationProduct::orderBy('category_id')->orderBy('name')->paginate(25))
    	->withCategories($categories);
  }

  public function create()
  {
    return View::make('admin::corporation_products_importer.create')->withCorporationProducts(new CorporationProduct);
  }

  public function store()
  {
    set_time_limit (300);
    ini_set('auto_detect_line_endings', 1);

    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminCorporationProductsController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
      ]));
    }

    $file = Input::file('file');
    $created = 0;
    $updated = 0;
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

      $reader->each(function($sheet)use(&$created, &$updated){
       
       
       $category = CorporationCategory::firstOrCreate(['name' => $sheet->getTitle()]);
       $sheet->each(function($row)use(&$created, &$updated, &$category){

          $corporation_product = CorporationProduct::firstOrNew([
            'name' => $row->descripcion_de_articulo,
            'description' => $row->descripcion_de_articulo,
            'max_stock' => $row->max_stock_mensual, 
            'measure_unit' => $row->unidad_de_medida 
            ]);
      
          if(!$corporation_product->exists and $corporation_product->save()){
              $created++;  
          }else{
          	Log::debug($corporation_product->getErrors());
          }
        
        });
        
      });
    });
   
       return Redirect::to(action('AdminCorporationProductsController@index'))->withSuccess("Se agregaron $created registros.");
  }

  public function edit($product_corporation_id)
  {
    $corporation_product = CorporationProduct::find($product_corporation_id);
    if(!$corporation_product){
      return Redirect::to(action('AdminCorporationProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::corporation_products_importer.create')->withCorporationProducts($corporation_product);
    }
  }

  public function update($product_corporation_id)
  {
    $corporation_product = CorporationProduct::find($product_corporation_id);
    if(!$corporation_product){
      return Redirect::to(action('AdminCorporationProductsController@index'))->withErrors('No se encontró el producto Corporation');
    }else{
      $corporation_product->fill(Input::all());
      if(Input::has('corporation_product_category_id') and Input::get('corporation_product_category_id')){
        $corporation_product->category_id = Input::get('corporation_product_category_id');
        
      }
      if($corporation_product->save()){
        return Redirect::to(action('AdminCorporationProductsController@index'))->withSuccess('Se ha actualizado el product Corporation');
      }else{
        return View::make('admin::corporation_products_importer.create')->withCorporationProducts($corporation_product);
      }
    }
  }


}