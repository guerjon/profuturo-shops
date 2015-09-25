<?

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;


class FurnitureCategory extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait;
  protected $rules = [
    'name' => 'required'
  ];
  protected $fillable = ['name', 'image'];

  public function __construct($attributes = array()){

    $this->hasAttachedFile('image', [
      'styles' => [
        'medium' => '300x300',
        'thumb' => '100x100',
        'mini' => '50x50'
        ]
      ]);
    parent::__construct($attributes);
  }

  public function furnitures()
  {
    return $this->hasMany('Furniture');
  }

  public function furnitureSubcategories(){
    return $this->hasMany('FurnitureSubcategory');
  }

}
