<?

class ApiController extends BaseController
{

  public function postAddToCart()
  {
    $q = Input::get('quantity');
    if($q <= 0){
      return Response::json([
        'status' => 500,
        'error_msg' => 'La cantidad debe ser un entero positivo menor o igual a 5'
        ]);
    }

    $product = Product::find(Input::get('product_id'));

    if(!$product){
      return Response::json([
        'status' => 500,
        'error_msg' => 'No se encontró el producto'
        ]);
    }

    if(Auth::user()->cart_products->contains($product->id)){
      $q += Auth::user()->cartProducts()->where('id', $product->id)->first()->pivot->quantity;
      Auth::user()->cartProducts()->detach($product->id);
    }

    Auth::user()->cartProducts()->attach($product->id, ['quantity' => $q]);


    return Response::json([
      'status' => 200,
      'msg' => "Se han añadido $q piezas al carrito",
      'product_id' => $product->id,
      'new_q' => $q,
      ]);
  }

  public function postRemoveFromCart()
  {
    $q = Input::get('quantity');
    if($q <= 0){
      return Response::json([
        'status' => 500,
        'error_msg' => 'La cantidad debe ser un entero positivo'
        ]);
      }

    $product = Product::find(Input::get('product_id'));

    if(!$product){
      return Response::json([
        'status' => 500,
        'error_msg' => 'No se encontró el producto'
        ]);
    }

    if(!Auth::user()->cart_products->contains($product->id)){
      return Response::json([
        'status' => 200,
        'new_q' => 0,
        'product_id' => $product->id,
        ]);
    }

    $q = Auth::user()->cartProducts()->where('id', $product->id)->first()->pivot->quantity - $q;
    Auth::user()->cartProducts()->detach($product->id);
    if($q <= 0){
      $q = 0;
    }else{
      Auth::user()->cartProducts()->attach($product->id, ['quantity' => $q]);
    }

    return Response::json([
      'status' => 200,
      'new_q' => $q,
      'product_id' => $product->id,
      ]);

  }

  public function getRequestInfo($request_id){
    $request = GeneralRequest::find($request_id);
    if($request){
      return Response::json([
        'status' => 200,
        'request' => $request->toArray()
        ]);
    }
  }

  public function postDestroyProducts()
  {
    $quantity   = Input::get('quantity');
    $order_id   = Input::get('order_id');
    $product_id = Input::get('product_id');
    Log::info('quantity'.$quantity);
    Log::info('order_id'.$order_id);
    Log::info('product_id'.$product_id);
    $order = Order::find($order_id);
    if((($order->products()->where('products.id',$product_id)->first()->pivot->quantity) -$quantity) == 0 ){
        DB::table('order_product')
    ->where('order_id','=',$order_id)
    ->where('product_id','=',$product_id)
    ->delete();   
  }else{
     DB::table('order_product')
    ->where('order_id','=',$order_id)
    ->where('product_id','=',$product_id)
    ->update(array('quantity'=> DB::raw('quantity-1')));
  } 


     return Response::json([
        'status' => 200,
        'error_msg' => 'No se encontró el producto'
        ]);
  }
}
