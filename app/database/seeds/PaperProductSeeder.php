<?

class PaperProductSeeder extends Seeder
{

  public function run()
  {
    if($h = fopen('app/database/csvs/PRODUCTOS.csv', 'r')){
      $category = Category::firstOrCreate([
        'name' => 'Papelería',
        ]);
      while($row = fgetcsv($h)){
        $product = new Product([
          'name' => $row[3],
          'model' => $row[6],
          'description' => $row[4]
          ]);
        $subcategory = Subcategory::firstOrCreate([
          'name' => $row[8],
          'category_id' => $category->id,
          ]);
        $product->category_id = $category->id;
        $product->subcategory_id = $subcategory->id;
        $product->save();
      }
    }
  }
}
