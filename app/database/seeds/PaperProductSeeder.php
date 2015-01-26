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
          'description' => $row[4]
          ]);
        $category = Category::firstOrCreate([
          'name' => $row[8],
          ]);
        $product->category_id = $category->id;
        $product->save();
      }
    }
  }
}
