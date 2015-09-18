<? 

class Divisional extends Eloquent{

	public function regions(){
		return $this->hasMany('Region');
	}

	public function users(){
		return $this->hasMany('User');
	}

}