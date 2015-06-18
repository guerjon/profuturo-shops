<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Furniture extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait,SoftDeletingTrait;

  protected $fillable = ['name', 'description','max_stock','measure_unit','sku','id_peole_soft','specific_description'
                        ,'surface','unitary','key','delivery_time','business_conditions','furniture_category_id'];

  protected $rules = [
    'name' => 'required',
    // 'price' => ['required', 'regex:/^(\d)+(\.\d+)?$/'],
  ];

  public static function boot()
  {
    parent::boot();
    parent::bootStapler();
    Product::deleting(function($furniture){
        DB::table('cart_furnitures')->where('furniture_id', $furniture->id)->delete();
    });
  }

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
