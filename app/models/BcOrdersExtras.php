<?

class BcOrdersExtras extends Eloquent
{

  protected $rules = [];
  protected $guarded = [];

  public function bcOrder()
  {
    return $this->belongsTo('BcOrder');
  }

}
