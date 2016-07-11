<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FurnitureOrder extends Eloquent
{

  use SoftDeletingTrait;

  protected $guarded = [''];

  protected $appends = ['readable_status'];

  public function furnitures()
  {
    return $this->belongsToMany('Furniture')
              ->withPivot(
                  'quantity',
                  'status',
                  'comments',
                  'company',
                  'assets',
                  'ccostos',
                  'color',
                  'id_active',
                  'request_price',
                  'request_description',
                  'request_quantiy_product',
                  'request_comments',
                  'request_product_id'
              )->withTrashed();
  }


  public function user()
  {
    return $this->belongsTo('User');
  }


  public function orderComplain()
  {
    return $this->hasOne('FurnitureOrderComplain');
  }

  public function getReadableStatusAttribute()
  {
      switch ($this->status) {
        case '0':
            return "EN PROCESO";
        case '1':
          return "PRODUCTO COTIZADO";
        case '2':
          return "PRODUCTO SELECCIONADO";
        case '3':
          return 'ORDEN CONFIRMADA';
        default:
          return "DESCONOCIDO";
      }
  }

}
