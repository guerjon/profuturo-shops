<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class CorporationProduct extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait,SoftDeletingTrait;

  protected $fillable = ['name','description', 'image', 'max_stock', 'measure_unit','corporation_category_id'];

  protected $rules = [
    'name' => 'required',
  ];

  protected $dates = ['deleted_at'];
  public static function boot()
  {
    parent::boot();
    parent::bootStapler();
    Product::deleting(function($product){
        DB::table('cart_corporation_products')->where('corporation_product_id', $product->id)->delete();
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

  public function category()
  {
    return $this->belongsTo('CorporationCategory');
  }
}
