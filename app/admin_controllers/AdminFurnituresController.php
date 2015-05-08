<?php

class AdminFurnituresController extends AdminBaseController
{


   public function index()
  {
    return View::make('admin::furnitures.index')->withFurnitures(Furniture::withTrashed()->orderBy('furniture_category_id')->orderBy('name')->paginate(25));
  }

  public function create()
  {
    return View::make('admin::furnitures.create')->withFurniture(new Furniture);
  }

  public function store()
  {
    $furniture = new Furniture(Input::all());
    if(Input::has('category_id') and Input::get('category_id')){
      $furniture->furniture_category_id = Input::get('category_id');
    }
    $furniture->name = Input::get("name");
    $furniture->specific_description = Input::get("specific_description");
    
    
    if($furniture->save()){
      return Redirect::to(action('AdminFurnituresController@index'))->withSuccess('Se ha creado el nuevo mueble');
    }else{
      return View::make('admin::furnitures.create')->withfurniture($furniture);
    }
  }

  public function edit($furniture_id)
  {
    $furniture = Furniture::find($furniture_id);
    if(!$furniture){
      return Redirect::to(action('AdminFurnituresController@index'))->withErrors('No se encontró el mueble');
    }else{
        return View::make('admin::furnitures.create')->withfurniture($furniture);
    }
  }

  public function update($furniture_id)
  {
    $furniture = Furniture::find($furniture_id);
    if(!$furniture){
      return Redirect::to(action('AdminFurnituresController@index'))->withErrors('No se encontró el mueble');
    }else{
      $furniture->fill(Input::all());
      if(Input::has('furniture_category_id') and Input::get('furniture_category_id')){
        $furniture->category_id = Input::get('furniture_category_id');
        
      }
      if($furniture->save()){
        return Redirect::to(action('AdminFurnituresController@index'))->withSuccess('Se ha actualizado el mueble');
      }else{
        return View::make('admin::furnitures.create')->withfurniture($furniture);
      }
    }
  }

   public function destroy($furniture_id){
    $furniture = Furniture::withTrashed()->find($furniture_id);
    if($furniture->trashed()){
      $furniture->restore();
      return Redirect::to(action('AdminFurnituresController@index'))->withSuccess('Se ha habilitado el producto');
    }else{
      $furniture->delete();
      return Redirect::to(action('AdminFurnituresController@index'))->withInfo('Se ha inabilitado el producto');
    }
  }

}
