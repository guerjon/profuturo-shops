	<?

class ColorSeeder extends Seeder
{

  public function run(){
    
    $colors =
    array("#FFD1D1","#FFD1F3","#D3D1FF","#D1E5FF",
    	  "#D1FCFF","#D1FFE7","#D6FFD1","#FEFFD1",
    	  "#FFEBD1","#FFD1D1");
    
    foreach ($colors as $color){
    	$color = Color::create(['color' => $color]);
    }
    
  }
}
