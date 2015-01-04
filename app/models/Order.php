<?php

class Order extends Eloquent
{

  protected $fillable = ['comments'];
  public function products()
  {
    return $this->belongsToMany('Product')->withPivot('quantity');
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
