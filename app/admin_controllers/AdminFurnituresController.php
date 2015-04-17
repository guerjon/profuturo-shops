<?php

class AdminFurnituresController extends AdminBaseController
{


   public function index()
  {
    return View::make('admin::furnitures.index')->withfurnitures(Furniture::orderBy('category_id')->orderBy('name')->paginate(25));
  }

  public function create()
  {
    return View::make('admin::furnitures.create')->withfurniture(new Furniture);
  }



  public function store()
  {
    $furniture = new Furniture(Input::all());
    if(Input::has('category_id') and Input::get('category_id')){
      $furniture->category_id = Input::get('category_id');
    }
    $furniture->name = Input::get("name");
    $furniture->description = Input::get("description");
    $furniture->image = Input::file('image');
    
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
      return Redirect::to(action('AdminFurnituresController@index'))->withErrors('No se encontró el furnitureo');
    }else{
      $furniture->fill(Input::all());
      if(Input::has('category_id') and Input::get('category_id')){
        $furniture->category_id = Input::get('category_id');
        
      }
      if($furniture->save()){
        return Redirect::to(action('AdminFurnituresController@index'))->withSuccess('Se ha actualizado el furnitureo');
      }else{
        return View::make('admin::furnitures.create')->withfurniture($furniture);
      }
    }
  }
}
