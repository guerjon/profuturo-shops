<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CorporationOrder extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];

  public function products()
  {
    return $this->belongsToMany('CorporationProduct','corporation_order_corporation_product','corp_order_id','corp_product_id')->withPivot('quantity', 'status', 'comments')->withTrashed();
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('CorporationOrderComplain');
  }

}
