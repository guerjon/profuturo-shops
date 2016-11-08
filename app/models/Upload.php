<?php


class Upload extends Eloquent 
{
	

  	public function user()
  	{
  		return $this->belongsTo('User');
  	}

}