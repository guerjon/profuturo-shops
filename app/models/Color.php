<?

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;


class Color extends Eloquent 
{

  use EloquentTrait, ValidatingTrait;
  protected $rules = [
    'color' => 'required'
  ];
  protected $fillable = ['color'];


  public function user()
  {
    return $this->hasOne('User');
  }

  
}
