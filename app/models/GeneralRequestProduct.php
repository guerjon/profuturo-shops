<?



class GeneralRequestProduct extends Eloquent{
	

  
  protected $rules = [];
  protected $guarded = [];

	public function generalRequest(){
		return $this->belongsTo('GeneralRequest');
	}	

}