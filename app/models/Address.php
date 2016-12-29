<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Address extends Eloquent
{

	use SoftDeletingTrait;

	protected $table = "address";
	protected $guarded = ['created_at', 'updated_at'];
	protected $dates = ['deleted_at'];

	public function users()
	{
		return $this->hasMany('User');
	}


	public function divisional()
	{
		return $this->belongsTo('Divisional');
	}

	public function region()
	{
		return $this->belongsTo('Region');
	}
}

