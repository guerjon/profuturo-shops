<?

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BcOrder extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $rules = [];
  protected $guarded = [];

  public function businessCards()
  {
    return $this->belongsToMany('BusinessCard')->withPivot('quantity', 'status', 'comments','direccion_alternativa_tarjetas')->withTrashed();
  }

  public function user()
  {
    return $this->belongsTo('User');
  }

    public function extra()
  {
    return $this->hasOne('BcOrdersExtras');
  }
}
