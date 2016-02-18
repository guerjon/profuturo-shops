<?php

class MacOrderComplain extends Eloquent
{
  protected $fillable = ['complain'];

  public function order()
  {
    return $this->belongsTo('MacOrder');
  }
}
