<?

class ColorSeeder extends Seeder
{

  public function run(){
    
    $colors =
    array("#43C743","#329595","#FFA154","#F9F354","#CFFC54","#F150C0","#AB59ED","#FF5454","#FFA154","#43C743");
    
    foreach ($colors as $color){
    	$color = Color::create(['color' => $color]);
    }
    
  }
}
