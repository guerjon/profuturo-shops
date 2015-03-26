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
            'quantity' => $row[3]
          ];
        }


      }
    }


    if($h = fopen('app/database/csvs/bc_orders.csv', 'r')){
    
      $current_order = new BcOrder;
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
                $current_order->businessCards()->attach($p['id'], ['quantity' => $p['quantity']]);
              }
              if($row[14] > 0){
                DB::table('blank_cards_bc_order')->insert([
                  'bc_order_id' => $current_order->id,
                  'quantity' => $row[15],
                  'status' => $row[16],
                  'comments' => $row[17],
                  ]);
              }
            }
          }
          $current_order = new BcOrder;
          $prev_id = $row[0];
          $products = [];
        }else{
          $current_order->comments = $row[count($row)-1];
          $user = User::where('ccosto', $row[1])->first();
          $card = BusinessCard::where('nombre', $row[3])->first();
          if(!$card){
            $this->command->info("No se encontró la tarjeta de {$row[3]}");
            $card = BusinessCard::create([
              'no_emp' => $row[18],
              'nombre' => $row[3],
              'ccosto' => $row[4],
              'nombre_ccosto' => $row[5],
              'nombre_puesto' => $row[6],
              'fecha_ingreso' => \Carbon\Carbon::createFromFormat('Y-m-d', $row[7]),
              'web' => $row[8],
              'gerencia' => $row[9],
              'direccion' => $row[10],
              'telefono' => $row[11],
              'celular' => $row[12],
              'email' => $row[13]
              ]);
          }else{
            $card->fill([
              'nombre_ccosto' => $row[5],
              'nombre_puesto' => $row[6],
              'fecha_ingreso' => \Carbon\Carbon::createFromFormat('Y-m-d', $row[7]),
              'web' => $row[8],
              'gerencia' => $row[9],
              'direccion' => $row[10],
              'telefono' => $row[11],
              'celular' => $row[12],
              'email' => $row[13]
              ]);
            $card->save();
          }
          $products[] = [
            'id' => $card->id,
            'quantity' => 100
          ];
        }
      }
    }
  }
}
