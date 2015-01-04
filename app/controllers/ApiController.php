<?

class ApiController extends BaseController
{

  public function getSubcategories($category_id)
  {
    $category = Category::find($category_id);
    if($category){
      return Response::json([
        'status' => 200,
        'subcategories' => $category->subcategories->toArray()
        ]);
    }else{
      return Response::json([
        'status' => 404
        ]);
    }

  }

  public function postAddToCart()
  {
    $q = Input::get('quantity');
    if($q <= 0 or $q > 5){
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
}
