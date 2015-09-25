<?

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;


class FurnitureSubcategory extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait;
  protected $rules = [
    'name' => 'required'
  ];
  protected $fillable = ['name'];

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

  public function furnitureCategory()
  {
    return $this->belongsTo('FurnitureCategory');
  }

  
}
