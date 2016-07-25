<?

use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BusinessCard extends Eloquent
{
  use ValidatingTrait,SoftDeletingTrait;
  protected $rules = [
    'no_emp' => 'required|numeric',
    'nombre' => 'required',
    'ccosto' => 'required|numeric'
  ];
  protected $guarded = [];

  protected $dates = ['deleted_at'];
  
  public function setFechaIngresoAttribute($value)
  {
    if(is_string($value)){
      try {
        $this->attributes['fecha_ingreso'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value);  
      } catch (Exception $e) {
        $this->attributes['fecha_ingreso'] = $value;  
      }
      
    }else{
      $this->attributes['fecha_ingreso'] = $value;
    }

  }
}
