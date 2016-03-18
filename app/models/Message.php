<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Message extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $guarded = [''];
  protected $table = 'messages';

  public function users()
  {
      return $this->belongsToMany('User');
  }

}
