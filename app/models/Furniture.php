<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;


class Furniture extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait;

  protected $fillable = ['name', 'description','max_stock','measure_unit','sku','id_peole_soft','specific_description'
                        ,'surface','unitary','key','delivery_time','business_conditions','category_id'];

  protected $rules = [
    'name' => 'required',
    // 'price' => ['required', 'regex:/^(\d)+(\.\d+)?$/'],
  ];

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

  public function category()
  {
    return $this->belongsTo('FurnitureCategory');
  }

}
