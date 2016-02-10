<?php

class Address extends Eloquent
{

  protected $table = "address";
  protected $fillable = ['ccostos','gerencia','regional','divicional','domicilio','inmueble','posible_cambio'];

}
