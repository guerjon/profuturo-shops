<? 

class Divisional extends Eloquent{


protected $fillable = ['id'];

protected $guarded = ['updated_at','created_at'];

	public function regions(){

		return $this->hasMany('Region');
	}

	public function users(){
		return $this->hasMany('User');
	}

}