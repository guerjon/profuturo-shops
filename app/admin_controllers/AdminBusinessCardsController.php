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



    if (Input::has('excel')) {
      $headers = 
        [
          "numero_empleado",
          "nombre_empleado",
          "ccosto",
          "nombre_ccosto",
          "nombre_puesto",
          "fecha_ingreso",
          "web",
          "gerencia",
          "direccion",
          "direccion_alternativa",
          "telefono",
          "celular",
          "email"
        ];
        
        $datetime = \Carbon\Carbon::now()->format('YmdHi');

        $cards = $cards->get();
        Excel::create('tarjetas_activas', function($excel) use($cards,$headers){
          $excel->sheet('tarjetas',function($sheet)use($cards,$headers){
            $sheet->appendRow($headers);
            foreach ($cards as $card) {
              $sheet->appendRow([
                $card->no_emp,
                $card->nombre,
                $card->ccosto,
                $card->nombre_ccosto,
                $card->nombre_puesto,
                $card->fecha_ingreso,
                $card->web,
                $card->gerencia,
                $card->direccion,
                $card->direccion_alternativa,
                $card->telefono,
                $card->celular,
                $card->email                
              ]);
            }
          });
        })->download('xls');
    }else{
        return View::make('admin::business_cards.index')
        ->withCards($cards->paginate(50))
        ->withGerencias($gerencias)
        ->withActiveTab($active_tab);  
    }

    
  }

  public function create()
  {
    return View::make('admin::business_cards.create');
  }

  public function store()
  { 
    set_time_limit (600);
    ini_set('auto_detect_line_endings', 1);

    if(Input::file('avatar') == NULL){
      return Redirect::to(action('AdminBusinessCardsController@create'))->withErrors(new MessageBag([
        'avatar' => 'El archivo es requerido',
      ]));
    }

    
    $file = Input::file('avatar');
    $user = Auth::user();
    $path = storage_path('paper_cards');
    $upload = new Upload();
    $upload->user_id = $user->id;
    $upload->file_name = $file->getClientOriginalName();
    $upload->file_size = $file->getClientSize();
    $upload->file_mime = $file->getMimeType();
    $upload->file_extension = $file->getClientOriginalExtension();
    $upload->kind = 'paper_cards';
    $file = $file->move($path);
    
    $upload->file_path = $file->getRealPath();
    

    $created = 0;
    $updated = 0;

    $business_cards =  BusinessCard::where('id','>','0')->where('type','=','paper')->delete();
    


    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {
        
      $reader->each(function($sheet)use(&$created, &$updated){

        $sheet->each(function($row)use(&$created, &$updated){
          //dd($row->fecha_ingreso);
          if($row->numero_empleado != '' && $row->numero_empleado != null){
            $card = BusinessCard::withTrashed()->where('no_emp',$row->numero_empleado)->where('type','paper')->first();
            try {
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
                    'linea_negocio' => $row->linea_negocio ? $row->linea_negocio : 'N/A'
                  ]);
                  if($card){
                    $created++;
                  }  
              
              }else{
                
                if($card->trashed())
                  $card->restore();

                $card->fill([
                  'no_emp' => $row->numero_empleado ? $row->numero_empleado : 'N/A',
                  'nombre' => $row->nombre_empleado ? $row->nombre_empleado : 'N/A',
                  'ccosto' => $row->ccosto ? $row->ccosto : 'N/A',
                  'nombre_ccosto' => $row->nombre_ccosto ? $row->nombre_ccosto : 'N/A',
                  'nombre_puesto' => $row->nombre_puesto ? $row->nombre_puesto : 'N/A',
                  'fecha_ingreso' => $row->fecha_ingreso,
                  'web' => $row->web ? $row->web : 'N/A',
                  'gerencia' => $row->gerencia ? $row->gerencia : 'N/A',
                  'direccion' => $row->direccion ? $row->direccion : 'N/A',
                  'telefono' => $card->telefono == '5555 5555' ? $row->telefono : $card->telefono,
                  'celular' => $row->celular ? $row->celular : 'N/A',
                  'email' => $row->email ? $row->email : 'N/A',
                  'linea_negocio' => $row->linea_negocio ? $row->linea_negocio : 'N/A'
                ]);

                if ($card->isDirty()) {
                    if ($card->save()) {
                      $updated++;  
                    }
                }
              }
            } catch (InvalidArgumentException $e) {
                Log::debug($e); 
              }
          }
        });
      });
    })->formatDates(true,'m-d-Y');
    
    $upload->cards_created = $created;
    $upload->cards_updated = $updated;
    $upload->save();

    return Redirect::to(action('AdminBusinessCardsController@index'))
      ->withSuccess("Se agregaron $created registros. Se actualizaron $updated");
      
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
