<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Order extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];
  protected $dates = ['deleted_at'];

  public function products()
  {
    return $this->belongsToMany('Product')->withPivot('quantity', 'status', 'comments')->withTrashed();
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
