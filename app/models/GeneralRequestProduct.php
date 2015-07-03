<?



class GeneralRequestProduct extends Eloquent{
	

  
  protected $rules = [];
  protected $guarded = [];
  protected $appends = ['total'];

	public function generalRequest()
	{
		return $this->belongsTo('GeneralRequest');
	}	

	 public function getTotalAttribute()
  {	
    return $this->quantity*$this->unit_price;
  }

}