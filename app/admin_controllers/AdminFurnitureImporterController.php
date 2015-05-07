<?php

class AdminFurnitureImporterController extends AdminBaseController
{


   public function index()
  {
    return View::make('admin::furnitures.index')->withfurnitures(Furniture::orderBy('furniture_category_id')->orderBy('name')->paginate(25));
  }

  public function create()
  {
    return View::make('admin::furnitures_importer.create')->withfurniture(new Furniture);
  }

  public function store()
  {
    set_time_limit (300);
    ini_set('auto_detect_line_endings', 1);

    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminFurnitureImporterController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
      ]));
    }


    $file = Input::file('file');
    $created = 0;
    $updated = 0;
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

      $reader->each(function($sheet)use(&$created, &$updated){
       $category = new FurnitureCategory;
       $category->name = $sheet->getTitle();
       $category->save();
        $sheet->each(function($row)use(&$created, &$updated, &$category){

          $product = Furniture::create([
            'name' => $row->descripcion_profuturo,
            'max_stock' => 1, 
            'measure_unit' => 'Pieza' ,
            'sku' => '0', 
            'id_peole_soft' => $row->id_people_soft,
            'specific_description' => $row->descripcion_especifica_del_bien,
            'surface'=> $row->acabado,
            'unitary' => $row->unitario,
            'key' => $row->clave,
            'delivery_time' => $row->tiempo_de_entrega,
            'business_conditions' => $row->condiciones_comerciales,
            'furniture_category_id' => $category->id,
            ]);
        
          Log::info($category);
          if($product->save()){
          $created++;
        }
        });
        
      });
    });
   
       return Redirect::to(action('AdminFurnitureImporterController@index'))->withSuccess("Se agregaron $created registros.");
  }

  public function edit($furniture_id)
  {
    $furniture = Furniture::find($furniture_id);
    if(!$furniture){
      return Redirect::to(action('AdminFurnituresController@index'))->withErrors('No se encontró el mueble');
    }else{
        return View::make('admin::furnitures_importer.create')->withfurniture($furniture);
    }
  }

  public function update($furniture_id)
  {
    $furniture = Furniture::find($furniture_id);
    if(!$furniture){
      return Redirect::to(action('AdminFurnituresController@index'))->withErrors('No se encontró el furnitureo');
    }else{
      $furniture->fill(Input::all());
      if(Input::has('furniture_category_id') and Input::get('furniture_category_id')){
        $furniture->category_id = Input::get('furniture_category_id');
        
      }
      if($furniture->save()){
        return Redirect::to(action('AdminFurnituresController@index'))->withSuccess('Se ha actualizado el furnitureo');
      }else{
        return View::make('admin::furnitures_importer.create')->withfurniture($furniture);
      }
    }
  }

}
