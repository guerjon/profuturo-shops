<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Order extends Eloquent
{

  use SoftDeletingTrait;

  protected $guarded = [''];


  public function furnitures()
  {
    return $this->belongsToMany('Furniture')->withPivot('quantity', 'status', 'comments');
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('OrderComplain');
  }

}
