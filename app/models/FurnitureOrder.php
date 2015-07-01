<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FurnitureOrder extends Eloquent
{

  use SoftDeletingTrait;

  protected $guarded = [''];


  public function furnitures()
  {
    return $this->belongsToMany('Furniture')->withPivot('quantity', 'status', 'comments','company','assets','ccostos','color','id_active')->withTrashed();
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('FurnitureOrderComplain');
  }

}
