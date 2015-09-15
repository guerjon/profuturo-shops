<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Region extends Eloquent
{
	
  public function user()
  {
    return $this->hasMany('User');
  }

}