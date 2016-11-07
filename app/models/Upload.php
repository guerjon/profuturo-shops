<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;


class Upload extends Eloquent implements StaplerableInterface
{
	
	use EloquentTrait;


	protected $fillable = ['avatar','user_id','cards_created','cards_updated'];

    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('avatar', [
            'styles' => [
                'medium' => '300x300',
                'thumb' => '100x100'
            ]
        ]);

        parent::__construct($attributes);
    }

  	public function user()
  	{
  		return $this->belongsTo('User');
  	}



}