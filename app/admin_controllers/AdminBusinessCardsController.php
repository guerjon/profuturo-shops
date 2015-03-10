<?
use Illuminate\Support\MessageBag;
class AdminBusinessCardsController extends BaseController{

  public function index()
  {
    return View::make('admin::business_cards.index')->withCards(BusinessCard::orderBy('gerencia')->paginate(50));
  }

  public function create()
  {
    return View::make('admin::business_cards.create');
  }

  public function store()
  {
   

    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminBusinessCardsController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
        ]));
      }

      $file = Input::file('file');

   $excel = Excel::load($file->getRealPath(), function($reader) {
    
    $reader->each(function($sheet) {
      $sheet->each(function($row){
        BusinessCard::create([
        'no_emp' => $row->NUMERO_EMPLEADO,
        'nombre' => $row->NOMBRE_EMPLEADO,
        'ccosto' => $row->CCOSTO,
        'nombre_ccosto' => $row->NOMBRE_CCOSTO,
        'nombre_puesto' => $row->NOMBRE_PUESTO,
        'fecha_ingreso' => $row->FECHA_INGRESO,
        'web' => $row->WEB,
        'gerencia' => $row->GERENCIA,
        'direccion' => $row->DIRECCION,
        'telefono' => $row->TELEFONO,
        'celular' => $row->CELULAR,
        'email' => $row->EMAIL,
        ]);
      });
    });
  });


    return Redirect::to(action('AdminBusinessCardsController@index'))->withSuccess("Se agregaron  registros. Se actualizaron  ");
  }

  public function destroy($card_id)
  {
    $card = BusinessCard::find($card_id);
    if(!$card){
      return Redirect::to('/')->withErrors('No se encontró la tarjeta de presentación');
    }else{
      $card->delete();
      return Redirect::to(action('AdminBusinessCardsController@index'))->withSuccess('Se ha eliminado la tarjeta de presentación');
    }
  }
}
