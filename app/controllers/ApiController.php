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

  public function postAddToCartFurnitures()
  {
    $user = Auth::user();
    $quantity = Input::get('quantity');
    $company = Input::get('company');
    $assets = Input::get('assets');
    $ccostos = Input::get('ccostos');
    $color = Input::get('color');


    if($quantity <= 0){
      return Response::json([
        'status' => 500,
        'error_msg' => 'La cantidad debe ser un entero positivo'
        ]);
    }

    $furniture = Furniture::find(Input::get('furniture_id'));

    if(!$furniture){
      return Response::json([
        'status' => 500,
        'error_msg' => 'No se encontró el mobiliario'
        ]);
    }

    if($user->cart_furnitures->contains($furniture->id)){
      $quantity += $user->cartFurnitures()->where('id', $furniture->id)->first()->pivot->quantity;
      $user->cartFurnitures()->detach($furniture->id);
    }


    $user->cartFurnitures()->attach($furniture->id,['quantity' => $quantity,
                                                    'company' => $company,
                                                    'assets' => $assets,
                                                    'ccostos' => $ccostos,
                                                    'color' => $color]);


    return Response::json([
      'status' => 200,
      'msg' => "Se han añadido $quantity piezas al carrito",
      'furniture_id' => $furniture->id,
      'new_q' => $quantity,
      'company' => $company,
      'assets' => $assets,
      'ccostos' => $ccostos,
      'color' => $color,
      ]);
  }

  public function postAddToCartMac()
  {
     $q = Input::get('quantity');
        if($q <= 0){
          return Response::json([
            'status' => 500,
            'error_msg' => 'La cantidad debe ser un entero positivo menor o igual a 5'
            ]);
        }

        $product = MacProduct::find(Input::get('product_id'));

        if(!$product){
          return Response::json([
            'status' => 500,
            'error_msg' => 'No se encontró el producto'
            ]);
        }

        if(Auth::user()->cart_mac->contains($product->id)){
          $q += Auth::user()->cartMac()->where('id', $product->id)->first()->pivot->quantity;
          Auth::user()->cartMac()->detach($product->id);
        }

        Auth::user()->cartMac()->attach($product->id, ['quantity' => $q]);


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

  public function postRemoveFromCartMac()
  {
    $q = Input::get('quantity');
    if($q <= 0){
      return Response::json([
        'status' => 500,
        'error_msg' => 'La cantidad debe ser un entero positivo'
        ]);
      }

    $product = MacProduct::find(Input::get('product_id'));

    if(!$product){
      return Response::json([
        'status' => 500,
        'error_msg' => 'No se encontró el producto'
        ]);
    }

    if(!Auth::user()->cart_mac->contains($product->id)){
      return Response::json([
        'status' => 200,
        'new_q' => 0,
        'product_id' => $product->id,
        ]);
    }

    $q = Auth::user()->cartMac()->where('id', $product->id)->first()->pivot->quantity - $q;
    Auth::user()->cartMac()->detach($product->id);
    if($q <= 0){
      $q = 0;
    }else{
      Auth::user()->cartMac()->attach($product->id, ['quantity' => $q]);
    }

    return Response::json([
      'status' => 200,
      'new_q' => $q,
      'product_id' => $product->id,
      ]);

  }



  public function postRemoveFromCartFurniture()
  {
    $q = Input::get('quantity');
    if($q <= 0){
      return Response::json([
        'status' => 500,
        'error_msg' => 'La cantidad debe ser un entero positivo'
        ]);
      }

    $furniture = Furniture::find(Input::get('furniture_id'));

    if(!$furniture){
      return Response::json([
        'status' => 500,
        'error_msg' => 'No se encontró el mobiliario'
        ]);
    }

    if(!Auth::user()->cart_furnitures->contains($furniture->id)){
      return Response::json([
        'status' => 200,
        'new_q' => 0,
        'furniture_id' => $furniture->id,
        ]);
    }

    $q = Auth::user()->cartFurnitures()->where('id', $furniture->id)->first()->pivot->quantity - $q;
    Auth::user()->cartFurnitures()->detach($furniture->id);
    if($q <= 0){
      $q = 0;
    }else{
      Auth::user()->cartFurnitures()->attach($furniture->id, ['quantity' => $q]);
    }

    return Response::json([
      'status' => 200,
      'new_q' => $q,
      'furniture_id' => $furniture->id,
      ]);

  }

  public function getRequestInfo($request_id){
    $request = GeneralRequest::find($request_id);
    if($request){
      return Response::json([
        'status' => 200,
        'request' => $request->toArray(),
        'products' =>$request->general_request_products,
        ]);
    }
  }


  public function postDestroyProducts()
  {
    $quantity   = Input::get('quantity');
    $order_id   = Input::get('order_id');
    $product_id = Input::get('product_id');
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

  public function postAddProduct()
  {
    $order_id = Input::get('order_id');
    $product_id = Input::get('product_id');
    $quantity = Input::get('quantity');
    $query =  DB::table('order_product')->select('product_id')
    ->where('order_id','=',$order_id)
    ->where('product_id','=',$product_id)->get();

    if(count($query) == 0){
      DB::table('order_product')->insert(
      ['order_id' => $order_id, 'product_id' => $product_id, 'quantity' => $quantity]);
   
     return Response::json([
        'status' => 200
        ]);
    }
    else{
      return Response::json([
        'status' => 500
        ]);
    }
    
  }

  public function postDestroyFurniture()
  {
    $quantity   = Input::get('quantity');
    $order_id   = Input::get('order_id');
    $furniture_id = Input::get('furniture_id');
    $order = FurnitureOrder::find($order_id);
    if((($order->furnitures()->where('furnitures.id',$furniture_id)->first()->pivot->quantity) -$quantity) == 0 ){
        DB::table('furniture_orders')
    ->where('order_id','=',$order_id)
    ->where('furniture_id','=',$furniture_id)
    ->delete();   
  }else{
     DB::table('furniture_furniture_order')
    ->where('furniture_order_id','=',$order_id)
    ->where('furniture_id','=',$furniture_id)
    ->update(array('quantity'=> DB::raw('quantity-1')));
  } 


     return Response::json([
        'status' => 200,
        'error_msg' => 'No se encontró el producto'
        ]);
  }

  public function postAddFurnitures()
  {
    $order_id = Input::get('order_id');
    $furniture_id = Input::get('furniture_id');
    $quantity = Input::get('quantity');
    $query =  DB::table('furniture_furniture_order')->select('furniture_id')
    ->where('furniture_order_id','=',$order_id)
    ->where('furniture_id','=',$furniture_id)->get();

    if(count($query) == 0){
      DB::table('furniture_furniture_order')->insert(
      ['furniture_order_id' => $order_id, 'furniture_id' => $furniture_id, 'quantity' => $quantity]);
   
     return Response::json([
        'status' => 200
        ]);
    }
    else{
      return Response::json([
        'status' => 500
        ]);
    }
    
  }


  public function getUser()
  {

    
    $ccosto = Input::get('ccostos');
    $user = User::with('region')->where('ccosto',$ccosto)->first();
    
    if($user){
      return Response::json([
        'status' => 200,
        'user' => $user
      ]);
    }else{
      return Response::json([
        'status' => 404
      ]);
    }
  }


  public function getCcostosAutocomplete()
  {
    
    $ccostos = User::where('role','!=','admin')->lists('ccosto');
 
    if(Request::ajax()){
      return Response::json([
        'status' => 200,
        'ccostos' => $ccostos
      ]);
    }
  }

}
