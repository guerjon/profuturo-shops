<?php


class Sede extends Eloquent
{

    protected $fillable = ['name', 'address'];

    public function region()
    {
        return $this->belongsTo('Region');
    }
}

