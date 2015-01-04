<?php

class OrderComplain extends Eloquent
{
  protected $fillable = ['complain'];

  public function order()
  {
    return $this->belongsTo('Order');
  }
}
