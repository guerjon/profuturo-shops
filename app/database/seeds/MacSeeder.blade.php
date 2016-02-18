<?

class MacSeeder extends Seeder
{

  public function run(){

    if($h = fopen('app/database/csvs/USUARIOS_MAC.csv', 'r')){
      while($row = fgetcsv($h)){
        $regional = Region::where('name','LIKE','%'.$row[0].'%')->first();

        $user = User::create([
          'region_id' => $regional ? $regional->id : 1,
          'ccosto' => $row[2],
          'nombre' => $row[3],
          'gerencia' => $row[1],
          'password' => 'password',
          'role' => 'user_mac',
          ]);
        if(!$user->exists){
          $this->command->info("No se pudo guardar el usuario {$user->ccosto}");
          $this->command->info($user->getErrors());
        }
      }
    }
  }
}
