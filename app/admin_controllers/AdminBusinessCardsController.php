<?

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
    ini_set('auto_detect_line_endings', 1);
    
    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminBusinessCardsController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
        ]));
      }

      $file = Input::file('file');

      $mime = $file->getMimeType();
      if(!in_array($mime, ['text/csv', 'application/csv', 'text/plain'])) {
        return Redirect::to(action('ImportController@getUpload'))->withErrors(new MessageBag([
          'mime' => 'El archivo subido no es un archivo CSV válido. Mime recibido: '.$mime
        ]));
    }
    $handle = fopen($file->getRealPath(), 'r');

    $created = 0;
    while(($row = fgetcsv($handle)) !== FALSE){
      if(BusinessCard::create([
        'no_emp' => $row[1],
        'nombre' => $row[2],
        'ccosto' => $row[3],
        'nombre_ccosto' => $row[4],
        'nombre_puesto' => $row[5],
        'fecha_ingreso' => $row[6],
        'rfc' => $row[7],
        'web' => $row[8],
        'gerencia' => $row[9],
        'direccion' => $row[10],
        'telefono' => $row[11],
        'celular' => $row[12],
        'email' => $row[13],
        ])){
        $created++;
      }
    }
    return Redirect::to(action('AdminBusinessCardsController@index'))->withSuccess("Se agregaron $created registros");
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
