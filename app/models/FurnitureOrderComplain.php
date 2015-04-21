<?php

class FurnitureOrderComplain extends Eloquent
{
  protected $fillable = ['complain'];

  public function furnitureOrder()
  {
    return $this->belongsTo('FurnitureOrder');
  }
}
