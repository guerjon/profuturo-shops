<?

class BusinessCard extends Eloquent
{
  protected $rules = [];
  protected $guarded = [];


  public function setFechaIngresoAttribute($value)
  {
    $this->attributes['fecha_ingreso'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value);
  }
}
