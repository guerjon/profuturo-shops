<?

class SedesSeeder extends Seeder
{

    public function run()
    {
        if($h = fopen('app/database/csvs/sedes.csv', 'r')){
            $headers = fgetcsv($h);
            while($row = fgetcsv($h)){
                while(count($row) < count($headers)) {
                    $row[] = null;
                }
                while(count($headers) < count($row)) {
                    $headers[] = null;
                }
                $row = array_combine($headers, $row);
                $sede = new Sede($row);
                if($name = $row['region']) {
                    $region = Region::where('name', 'like', "%$name%")->first();
                    if($region) {
                        $sede->region_id = $region->id;
                        $sede->save();
                    }
                }
            }
        }
    }
}