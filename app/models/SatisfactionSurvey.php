<?php

class SatisfactionSurvey extends Eloquent 
{

  protected $guarded = [];
  protected $appends = ['average'];

  public function generalRequest(){
    return $this->belongsTo('GeneralRequest');
  }

  public function getAverageAttribute()
  {
  	return ($this->question_one + $this->question_two + $this->question_three + $this->question_four + $this->question_five + $this->question_one)/6;
  }

}