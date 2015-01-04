<?

class Subcategory extends Eloquent
{
  protected $rules = [];
  protected $fillable = ['category_id', 'name'];

  public function category()
  {
    return $this->belongsTo('Category');
  }

}
