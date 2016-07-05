<?php

class TrainingOrderComplain extends Eloquent
{
  protected $fillable = ['complain'];

  public function order()
  {
    return $this->belongsTo('TrainingOrder');
  }
}
