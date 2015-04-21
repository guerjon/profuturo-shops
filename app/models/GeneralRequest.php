<?

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class GeneralRequest extends Eloquent
{

  use SoftDeletingTrait;
  
  protected $rules = [];
  protected $guarded = [];

  protected $dates = ['created_at', 'updated_at', 'project_date'];

  protected $appends = ['project_date_formatted', 'total'];

  public function user(){
    return $this->belongsTo('User');
  }

  public function manager(){
    return $this->belongsTo('User', 'manager_id');
  }

  public function getProjectDateFormattedAttribute()
  {
  	return $this->project_date->format('Y-m-d');
  }

  public function getTotalAttribute()
  {
  	return $this->quantity*$this->unit_price;
  }

  public function getStatusStrAttribute()
  {
    switch($this->status){
      case 0: return  'Pendiente';
      break;
      case 1: return  'En revisión';
      break;
      case 2: return  'En proceso';
      break;
      case 3: return  'Entregado';
      break;
      default: return 'Desconocido';
    }
  }
}
