<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Message extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];


  public function users()
  {
      return $this->belongsToMany('User');
  }



}
