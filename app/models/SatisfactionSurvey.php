<?php

class SatisfactionSurvey extends Eloquent 
{



  public function generalRequest(){
    return $this->belongsTo('GeneralRequest');
  }

}