<?

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class GeneralRequest extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $rules = [];
  protected $guarded = [];

  protected $dates = ['created_at', 'updated_at', 'project_date','deliver_date'];

  protected $appends = ['status_str'];
  protected $hidden = ['status_str'];

  public function user(){
    return $this->belongsTo('User');
  }

  public function manager(){
    return $this->belongsTo('User', 'manager_id');
  }

  public function generalRequestProducts(){
    return $this->hasMany('GeneralRequestProduct');
  }

  public function products()
  {
    return $this->belongsToMany('Product')->withPivot('quantity','unit_price');
  }

  public function satisfactionSurvey()
  {
    return $this->hasOne('SatisfactionSurvey');
  }

  public function getStatusStrAttribute()
  {
    switch($this->status){
      case 0: return  'Acabo de recibir tu solicitud, en breve me comunicare contigo';
      break;
      case 1: return  'En estos momentos estoy localizando los proveedores que pueden contar con el artículo que necesitas';
      break;
      case 2: return  'Me encuentro en espera de las cotizaciones por parte de los proveedores seleccionados';
      break;
      case 3: return  'Ya recibí las propuestas correspondientes, estoy en proceso de análisis de costo beneficio';
      break;
      case 4: return  'Te comparto el cuadro comparativo con las mejores ofertas de acuerdo a tu necesidad';
      break;
      case 5: return  'Recotizar';
      break;
      case 6: return  'Conforme a tu elección, ingresa tu solicitud en People Soft';
      break;
      case 7: return  'Ya se envió la orden de compra al proveedor';
      break;
      case 8: return  'La fecha de entrega de tu pedido es ';
      break;
      case 9: return  'Tu pedido llego en excelentes condiciones, en el domicilio y recibió';
      break;
      case 10: return  'Fue un placer atenderte, me apoyarías con la siguiente encuesta de satisfacción.';
      break;
      case 11: return 'La encuesta ha sido contestada';
      break;
      case 12: return 'Encuesta cancelada;';
      default: return 'Desconocido';
    }
  }
}
