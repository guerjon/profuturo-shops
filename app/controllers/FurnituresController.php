<?

class FurnituresController extends BaseController
{

  public function index($category_id = NULL)
  {

    $furnitures = Furniture::select('*');
    if(Input::has('name')){
      $furnitures->where('name', 'like', "%".Input::get('name')."%");
    }
    $activeCategory = FurnitureCategory::find($category_id);
 
    if($category_id != null){
      return View::make('furnitures.index')->with([
      'furnitures' => $furnitures->where('furniture_category_id','=',$category_id)->paginate(15),
      'categories' => FurnitureCategory::all(),
      'activeCategory' => @$activeCategory,
      ]);
    }else{
      return View::make('furnitures.index')->with([
      'furnitures' => $furnitures->paginate(15),
      'categories' => FurnitureCategory::all(),
      'activeCategory' => @$activeCategory,
      ]);
    }
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
