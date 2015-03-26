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
    $created = 0;
    $updated = 0;
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

      $reader->each(function($sheet)use(&$created, &$updated){

        $sheet->each(function($row)use(&$created, &$updated){

          $card = BusinessCard::where('no_emp',$row->numero_empleado)->first();

          if(!$card){
            $card = BusinessCard::create([
              'no_emp' => $row->numero_empleado,
              'nombre' => $row->nombre_empleado,
              'ccosto' => $row->ccosto,
              'nombre_ccosto' => $row->nombre_ccosto,
              'nombre_puesto' => $row->nombre_puesto,
              'fecha_ingreso' => $row->fecha_ingreso,
              'web' => $row->web,
              'gerencia' => $row->gerencia,
              'direccion' => $row->direccion,
              'telefono' => $row->direccion,
              'celular' => $row->celular,
              'email' => $row->email,
            ]);
            if($card){
              $created++;

            }
            Log::info($row->no_emp);
          }else{
            $card->fill([
              'nombre' => $row->nombre_empleado,
              'ccosto' => $row->ccosto,
              'nombre_ccosto' => $row->ccosto,
              'nombre_puesto' => $row->nombre_ccosto,
              'fecha_ingreso' => $row->fecha_ingreso,
              'web' => $row->web,
              'gerencia' => $row->gerencia,
              'direccion' => $row->direccion,
              'telefono' => $row->telefono,
              'celular' => $row->celular,
              'email' => $row->email,
            ]);

            if($card->save()){
              $updated++;
            }
          }
        });
      });
    });

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
