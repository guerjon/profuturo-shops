<?php

class FurnitureOrder extends Eloquent
{

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
