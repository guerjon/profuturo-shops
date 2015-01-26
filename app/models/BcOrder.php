<?

class BcOrder extends Eloquent
{
  protected $rules = [];
  protected $guarded = [];

  public function businessCards()
  {
    return $this->belongsToMany('BusinessCard')->withPivot('quantity', 'status', 'comments');
  }

  public function user()
  {
    return $this->belongsTo('User');
  }
}
