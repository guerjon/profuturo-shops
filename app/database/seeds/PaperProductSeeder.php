<?

class PaperProductSeeder extends Seeder
{

  public function run()
  {
    if($h = fopen('app/database/csvs/PRODUCTOS.csv', 'r')){
      while($row = fgetcsv($h)){
        $product = new Product([
          'name' => $row[3],
          'model' => $row[6],
          'description' => $row[4],
          'max_stock' => $row[5],
          'measure_unit' => $row[6],
          ]);
        $category = Category::firstOrCreate([
          'name' => $row[8],
          ]);

        $img = $row[9];
        $path = "app/database/imgs/$img";
        if(file_exists($path) and copy($path, $img)){
          $file = new Symfony\Component\HttpFoundation\File\UploadedFile($img, $img, NULL, filesize($img), NULL, TRUE);
          $product->image = $file;
        }
        $product->category_id = $category->id;
        $product->save();
      }
    }
  }
}
