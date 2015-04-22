<?

class FurnituresController extends BaseController
{

  public function index($category_id = NULL)
  {

    $furnitures = Furniture::select('*');

  

    if(Input::has('name')){
      $furnitures->where('name', 'like', "%".Input::get('name')."%");
    }
    // if(Input::has('category_id') and Input::get('category_id') !== NULL){
    //   if(Input::get('category_id') == 0){
    //     $furnitures->whereNull('category_id');
    //   }else{
    //     $furnitures->where('category_id', Input::get('category_id'));
    //   }
    // }
    return View::make('furnitures.index')->with([
      'furnitures' => $furnitures->paginate(15),
      'categories' => Category::all(),
      'activeCategory' => @$activeCategory,
      ]);
  }

  public function postReceive($order_id)
  {
    $order = FurnitureOrder::find($order_id);
    $complete = 1;
    foreach(Input::get('furniture') as $id => $furniture){
      $pivot = $order->furnitures()->where('id', $id)->first()->pivot;
      $complete *= $furniture['status'];
      $pivot->status = $furniture['status'];
      $pivot->comments = $furniture['comments'];
      $pivot->save();
    }

    if($complete){
      $order->status = $complete;
    }else{
      $order->status = 2;
    }


    $order->receive_comments = Input::get('receive_comments');
    $order->save();
    return Redirect::to(action('OrderFurnituresController@index'))->withSuccess('Se ha actualizado la información');
  }

}
