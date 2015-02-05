<?

class UserSeeder extends Seeder
{

  public function run(){
  if($h = fopen('app/database/csvs/USUARIOS.csv', 'r')){
    while($row = fgetcsv($h)){
      $user = User::create([
        'profuturo_assigned_number' => $row[1],
        'nomenclatura' => $row[2],
        'regional'  => $row[3],
        'gerencia' => $row[4],
        'first_name' => $row[5],
        'last_name' => 'PENDIENTE',
        'ccosto' => $row[6],
        'asistente' => $row[7],
        'address' => $row[8],
        'email' => $row[9],
        'phone' => $row[10],
        'password' => $row[11],
        'role' => 'user_paper'
        ]);
      }
    }
  }
}
