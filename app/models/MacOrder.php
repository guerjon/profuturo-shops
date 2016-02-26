<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MacOrder extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];

  public function products()
  {
    return $this->belongsToMany('MacProduct')->withPivot('quantity', 'status', 'comments')->withTrashed();
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('MacOrderComplain');
  }

}
