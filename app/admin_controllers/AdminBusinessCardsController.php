<?
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\File\File;

class AdminBusinessCardsController extends BaseController{

  public function index()
  {



    $active_tab = Input::get('active_tab','untrashed');

    $gerencias = BusinessCard::withTrashed()->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'gerencia');
    $cards = BusinessCard::withTrashed()->orderBy('gerencia')->orderBy('no_emp');

    
    if(Input::has('no_emp')){
      $cards->where('no_emp','like','%'.Input::get('no_emp').'%');
    }

    if(Input::has('gerencia')){
      $cards->where('gerencia', Input::get('gerencia'));
    }

    if(Input::has('ccosto')){
      $cards->where('ccosto','like','%'.Input::get('ccosto').'%');
    }
    if($active_tab == 'trashed')
      $cards->onlyTrashed();
    else{
      $cards->whereNull('deleted_at');
    }


    return View::make('admin::business_cards.index')
      ->withCards($cards->paginate(50))
      ->withGerencias($gerencias)
      ->withActiveTab($active_tab);
  }

  public function create()
  {
    return View::make('admin::business_cards.create');
  }

  public function store()
  { set_time_limit (300);
    ini_set('auto_detect_line_endings', 1);

    if(Input::file('file') == NULL){
      return Redirect::to(action('AdminBusinessCardsController@create'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
      ]));
    }

    $file = Input::file('file');

    $created = 0;
    $updated = 0;


    //$copy_file = copy($file->getRealPath(),storage_path('excel/'.$file->getClientOriginalName()));

    $business_cards =  BusinessCard::where('id','>','0')->where('type','=','paper')->delete();
    
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

      $reader->each(function($sheet)use(&$created, &$updated){
        $sheet->each(function($row)use(&$created, &$updated){
          $card = BusinessCard::withTrashed()->where('no_emp',$row->numero_empleado)->where('type','paper')->first();

          if(!$card){
            
            $card = BusinessCard::create([
              'no_emp' => $row->numero_empleado ? $row->numero_empleado : 'N/A',
              'nombre' => $row->nombre_empleado ? $row->nombre_empleado : 'N/A',
              'ccosto' => $row->ccosto ? $row->ccosto : 'N/A',
              'nombre_ccosto' => $row->nombre_ccosto ? $row->nombre_ccosto : 'N/A',
              'nombre_puesto' => $row->nombre_puesto ? $row->nombre_puesto : 'N/A',
              'fecha_ingreso' => $row->fecha_ingreso,
              'web' => $row->web ? $row->web : 'N/A',
              'gerencia' => $row->gerencia ? $row->gerencia : 'N/A',
              'direccion' => $row->direccion ? $row->direccion : 'N/A',
              'telefono' => $row->telefono ? $row->telefono : 'N/A',
              'celular' => $row->celular ? $row->celular : 'N/A',
              'email' => $row->email ? $row->email : 'N/A',
            ]);
            if($card){
              $created++;
            }
            
          }else{
            
            if($card->trashed())
              $card->restore();

            $card->fill([
              'no_emp' => $row->numero_empleado,
              'nombre' => $row->nombre_empleado,
              'ccosto' => $row->ccosto,
              'nombre_ccosto' => $row->nombre_ccosto ? $row->nombre_ccosto : 'N/A',
              'nombre_puesto' => $row->nombre_puesto,
              'fecha_ingreso' => $row->fecha_ingreso,
              'web' => $row->web,
              'gerencia' => $row->gerencia,
              'direccion' => $row->direccion,
              'telefono' => $row->telefono,
              'celular' => $row->celular,
              'email' => $row->email,
            ]);

            if ($card->isDirty()) {
                $updated++;
                $card->save();
            }
          }
        });
      });
    });


    $path = storage_path('excel/'.$file->getClientOriginalName());
    $name = $file->getClientOriginalName();

    //$file = new \Symfony\Component\HttpFoundation\File\UploadedFile($path, $name, NULL, NULL, NULL, TRUE);
    
    $upload = Upload::create(
      [
          'user_id' => Auth::user()->id,
          'cards_created' => $created,
          'cards_updated' => $updated,
          'file' => $file
      ]
    );    
    
    return Redirect::to(action('AdminBusinessCardsController@index'))
      ->withSuccess("Se agregaron $created registros. Se actualizaron $updated")
      ->withUpload($upload);
  }

  public function destroy($card_id)
  {
    $card = BusinessCard::withTrashed()->find($card_id);
    if(!$card)
    {
      return Redirect::to('/')->withErrors('No se encontró la tarjeta de presentación');
    }
    else{
      if($card->trashed()){
        $card->restore();
        return Redirect::to(action('AdminBusinessCardsController@index'))->withSuccess('Se ha restaurado la tarjeta de presentación');
      }else{
        $card->delete();
        return Redirect::to(action('AdminBusinessCardsController@index'))->withInfo('Se ha eliminado la tarjeta de presentación');
      }
    }
  }
}
