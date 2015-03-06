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
    $updated = 0;
    while(($row = fgetcsv($handle)) !== FALSE){

      $card = BusinessCard::where('no_emp', $row[1])->first();
      if(!$card){
        if(BusinessCard::create([
          'no_emp' => $row[1],
          'nombre' => $row[2],
          'ccosto' => $row[3],
          'nombre_ccosto' => $row[4],
          'nombre_puesto' => $row[5],
          'fecha_ingreso' => $row[6],
          'web' => $row[7],
          'gerencia' => $row[8],
          'direccion' => $row[9],
          'telefono' => $row[10],
          'celular' => $row[11],
          'email' => $row[12],
          ])){
          $created++;
        }
      }else{
        $card->fill([
          'nombre' => $row[2],
          'ccosto' => $row[3],
          'nombre_ccosto' => $row[4],
          'nombre_puesto' => $row[5],
          'fecha_ingreso' => $row[6],
          'web' => $row[7],
          'gerencia' => $row[8],
          'direccion' => $row[9],
          // 'telefono' => $row[10],
          // 'celular' => $row[11],
          'email' => $row[12],
        ]);
        if($card->save()){
          $updated++;
        }
      }

    }
    return Redirect::to(action('AdminBusinessCardsController@index'))->withSuccess("Se agregaron $created registros. Se actualizaron $updated");
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
