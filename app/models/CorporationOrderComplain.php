<?php

class CorporationOrderComplain extends Eloquent
{
  protected $fillable = ['complain'];

  public function order()
  {
    return $this->belongsTo('CorporationOrder');
  }
}
