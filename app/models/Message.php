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

  public static function sendMessage($sender_id,$receiver_id,$body)
  {
  	DB::table('messages')->insert(['sender_id' => $sender_id,'receiver_id' => $receiver_id,'body' => $body]);
  }

}
