<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TrainingOrder extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];

  public function products()
  {
    return $this->belongsToMany('TrainingProduct')->withPivot('quantity', 'status', 'comments')->withTrashed();
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('TrainingOrderComplain');
  }

}
