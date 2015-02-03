<?

class GeneralRequest extends Eloquent
{
  protected $rules = [];
  protected $guarded = [];

  protected $dates = ['created_at', 'updated_at', 'project_date'];
}
