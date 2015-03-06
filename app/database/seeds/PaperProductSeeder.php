<?

class PaperProductSeeder extends Seeder
{

  public function run()
  {
    if($h = fopen('app/database/csvs/PRODUCTOS.csv', 'r')){
      while($row = fgetcsv($h)){
        $product = new Product([
          'name' => $row[1],
          'model' => $row[2],
          'description' => $row[3],
          'max_stock' => $row[4],
          'measure_unit' => $row[5],
          'sku' => $row[1],
          ]);
        $name_category = $row[14];
        $name = '';
        if($name_category == 1){
           $name = 'PAPEL';  
          }
          if($name_category == 2){
           $name = 'ARTICULOS DE OFICINA';  
          }

          if($name_category == 3){
          $name = 'MATERIAL DE APOYO';  
          }
          
        $category = Category::firstOrCreate([
          'name' => $name]);

        $img = $row[9];
        $path = "app/database/imgs/$img";
        if(file_exists($path) and copy($path, $img)){
          $file = new Symfony\Component\HttpFoundation\File\UploadedFile($img, $img, 'image/png', filesize($img), NULL, TRUE);
          $product->image = $file;
        }
        $product->category_id = $category->id;
        try{
          $product->save();
        }catch(Exception $e){
          Log::error($e->getMessage());
          continue;
        }

      }
    }
  }
}
