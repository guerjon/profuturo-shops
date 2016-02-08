<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Product extends Eloquent implements StaplerableInterface
{

  use EloquentTrait, ValidatingTrait,SoftDeletingTrait;

  protected $fillable = ['name', 'model', 'description', 'image', 'max_stock', 'measure_unit', 'sku'];

  protected $rules = [
    'name' => 'required',
    // 'price' => ['required', 'regex:/^(\d)+(\.\d+)?$/'],
  ];

  public static function boot()
  {
    parent::boot();
    parent::bootStapler();
    Product::deleting(function($product){
        DB::table('cart_products')->where('product_id', $product->id)->delete();
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
    return $this->belongsTo('Category');
  }

}
