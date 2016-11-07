<?

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;


class Upload extends Eloquent implements StaplerableInterface
{
	
	use EloquentTrait;


	protected $fillable = ['user_id','file','cards_created','cards_update'];

	public static function boot()
	{
		parent::boot();
		parent::bootStapler();
	}

  	public function __construct($attributes = array()){

		$this->hasAttachedFile('file', [
			'styles' => [
			'medium' => '300x300',
			'thumb' => '100x100',
			'mini' => '50x50'
			]
			]);
		parent::__construct($attributes);
  	}

  	public function user()
  	{
  		return $this->belongsTo('User');
  	}



}