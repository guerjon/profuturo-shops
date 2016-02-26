<?php

class AdminMacProductsImporterController  extends AdminBaseController {


   public function index()
  {	$categories  = MacCategory::all();
    return View::make('admin::mac_products.index')
    	->withMacProducts(MacProduct::orderBy('category_id')->orderBy('name')->paginate(25))
    	->withCategories($categories);
  }

  public function create()
  {
    return View::make('admin::mac_products_importer.create')->withMacProducts(new MacProduct);
  }

  public function store()
  {
    set_time_limit (300);
    ini_set('auto_detect_line_endings', 1);

    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminMacProductsController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
      ]));
    }

    $file = Input::file('file');
    $created = 0;
    $updated = 0;
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

      $reader->each(function($sheet)use(&$created, &$updated){
       
       
       $category = MacCategory::firstOrCreate(['name' => $sheet->getTitle()]);
       $sheet->each(function($row)use(&$created, &$updated, &$category){

          $mac_product = MacProduct::firstOrNew([
            'name' => $row->descripcion_de_articulo,
            'description' => $row->descripcion_de_articulo,
            'max_stock' => $row->max_stock_mensual, 
            'measure_unit' => $row->unidad_de_medida 
            ]);
          // $img = $row->descripcion_profuturo.'.png';
          // $path = storage_path('imgs_furnitures')."/$img"; 
          // if(file_exists($path) and copy($path, $img)){
          //   // $file = new Symfony\Component\HttpFoundation\File\UploadedFile($img, $img, 'image/png', filesize($img), NULL, TRUE);
          //   $file = new Symfony\Component\HttpFoundation\File\File($img);
          // }else{
          //   $img = "no_disponible.png";
          //   $path = storage_path('imgs_furnitures')."/$img";
          //   if(copy($path, $img)){
          //     // $file = new Symfony\Component\HttpFoundation\File\UploadedFile($img, $img, 'image/png', filesize($img), NULL, TRUE);  
          //     $file = new Symfony\Component\HttpFoundation\File\File($img);  
          //   }else{
          //     $file = null;
          //   }
            
          // }
          // $mac_product->image = $file;
      
          if(!$mac_product->exists and $mac_product->save()){
              $created++;  
          }else{
          	Log::debug($mac_product->getErrors());
          }
        
        });
        
      });
    });
   
       return Redirect::to(action('AdminMacProductsController@index'))->withSuccess("Se agregaron $created registros.");
  }

  public function edit($product_mac_id)
  {
    $mac_product = MacProduct::find($product_mac_id);
    if(!$mac_product){
      return Redirect::to(action('AdminMacProductsController@index'))->withErrors('No se encontró el producto');
    }else{
        return View::make('admin::mac_products_importer.create')->withMacProducts($mac_product);
    }
  }

  public function update($product_mac_id)
  {
    $mac_product = MacProduct::find($product_mac_id);
    if(!$mac_product){
      return Redirect::to(action('AdminMacProductsController@index'))->withErrors('No se encontró el producto mac');
    }else{
      $mac_product->fill(Input::all());
      if(Input::has('mac_product_category_id') and Input::get('mac_product_category_id')){
        $mac_product->category_id = Input::get('mac_product_category_id');
        
      }
      if($mac_product->save()){
        return Redirect::to(action('AdminMacProductsController@index'))->withSuccess('Se ha actualizado el product mac');
      }else{
        return View::make('admin::mac_products_importer.create')->withMacProducts($mac_product);
      }
    }
  }


}