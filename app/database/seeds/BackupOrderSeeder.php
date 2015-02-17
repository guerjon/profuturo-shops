<?

class BackupOrderSeeder extends Seeder
{
  public function run()
  {
    if($h = fopen('app/database/csvs/orders.csv', 'r')){

      $current_order = new Order;
      $products = [];
      $prev_id = NULL;
      $user = new User;
      while($row = fgetcsv($h)){
        if($row[0] !== $prev_id){
          if(count($products) > 0){
            if(!$user or !$user->exists){
              $this->command->info("No se encontró el usuario");
            }else{
              $current_order->user_id = $user->id;
              $current_order->save();
              $this->command->info("Se guardó la orden {$prev_id} con nuevo id {$current_order->id}");
              foreach($products as $p){
                $current_order->products()->attach($p['id'], ['quantity' => $p['quantity']]);
              }
            }
          }
          $current_order = new Order;
          $prev_id = $row[0];
          $products = [];
        }else{
          $current_order->comments = $row[4];
          $user = User::where('ccosto', $row[1])->first();
          $product = Product::where('name', $row[2])->first();
          if(!$product){
            $this->command->info("No se encontró el producto {$row[2]}");
            continue;
          }
          $products[] = [
            'id' => $product->id,
            'quantity' => $row[4]
          ];
        }


      }
    }
  }
}
