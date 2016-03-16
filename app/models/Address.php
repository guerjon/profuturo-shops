<?php
class Address extends Eloquent
{

  protected $table = "address";
  protected $guarded = ['created_at', 'updated_at'];

public function users()
{
	return $this->hasMany('User');
}

}
