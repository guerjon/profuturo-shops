<? 

class Divisional extends Eloquent{


protected $fillable = ['id','name'];

protected $guarded = ['updated_at','created_at'];

	public function users(){
		return $this->hasMany('User')->withPivot('until','from');
	}

}