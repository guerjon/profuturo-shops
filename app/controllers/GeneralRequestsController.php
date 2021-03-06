<?

class GeneralRequestsController extends BaseController{

  public function index()
  {

    $active_tab = Input::get('active_tab', 'all');
    $access = Input::get('access',false);
  
    $assigneds = ['ASIGNADO','NO ASIGNADO'];
    $active_category = ['ASIGNADO','NO ASIGNADO'];

    $access = DB::table('general_requests')
                    ->where('user_id',Auth::user()->id)
                    ->where('general_requests.status',10)
                    ->where('deleted_at',null)
                    ->count();

    
    $surveys_answered = DB::table('general_requests')
                          ->join('satisfaction_surveys','satisfaction_surveys.general_request_id','=','general_requests.id')
                          ->where('user_id',Auth::user()->id)
                          ->count();                

    $access = $surveys_answered  == $access ? true : false;


    $requests = GeneralRequest::
      join('general_request_products','general_request_products.general_request_id','=','general_requests.id')
      ->select(DB::raw(
        '
          general_requests.*,
          count(general_request_products.id) as total_products,
          sum(general_request_products.unit_price) as total
          
        '))->where('user_id',Auth::id())->orderBy('rating')->groupBy('general_requests.id');

        if($active_tab == 'in_process'){
            $requests->where('status','>',0)->where('status','<',10);

        }elseif($active_tab == 'concluded'){
            Log::debug($requests->get());
          $requests->where('status',10);

        }elseif($active_tab == 'canceled'){
          $requests->onlyTrashed();
        }
        
    return View::make('general_requests.index')->withRequests($requests->get())
                                               ->withActiveCategory($active_category)
                                               ->withActiveTab($active_tab)
                                               ->withAccess($access);
  }

  public function store()
  {
 
    if(!(Auth::user()->num_empleado && Auth::user()->extension && Auth::user()->celular && Auth::user()->email && Auth::user()->gerencia) )
      return Redirect::back()->withErrors("El usuario no tiene número de empleado");

    $request = new GeneralRequest(Input::except('budget','quantity','unit_price','name'));
    /*agregando el valor adicional a los campos*/
    $request->employee_name = Auth::user()->gerencia;
    $request->employee_email = Auth::user()->email;
    $request->employee_cellphone = Auth::user()->celular;
    $request->employee_number = Auth::user()->num_empleado;
    $request->employee_ext = Auth::user()->extension;

    Auth::user()->generalRequests()->save($request);
      

    $quantities = Input::get('quantity');
    $unit_prices= Input::get('unit_price');
    $name       = Input::get('name');
    $products = sizeof($quantities) - 1;

    while($products != -1){
      //productos asociados a la solicitud general
      $general = new GeneralRequestProduct();
      
      $general->general_request_id = $request->id;
      $general->quantity = $quantities[$products];
      $general->unit_price = $unit_prices[$products];
      $general->name = $name[$products];
      if($general->name){
        $general->save();
      }
      $products--;
    }
    
    $user = Auth::user();
    
    $email = "KARINA.ASCENCIONHERNANDEZ@profuturo.com.mx";
    // $email = "jona_54_.com@ciencias.unam.mx";
    Mail::send('admin::email_templates.general_request_notice',['user' => $user,'general_request' => $general],function($message) use ($email){
      $message->to($email)->subject("Solicitud general hecha.");
    });

    $email = Auth::user()->email;

    Mail::send('admin::email_templates.general_request_notice',['user' => $user,'general_request' => $general],function($message) use ($email){
      $message->to($email)->subject("Tu solicitud general fue enviada.");
    });
    
    
    return Redirect::to(action('GeneralRequestsController@index'))->withSuccess("Se ha guardado su solicitud con id {$request->id}");
  }


  public function update($inutilizado){

    $status = Input::get('status');
    $id = Input::get('request_id');
    
    $request = GeneralRequest::find($id);

    $request->status = $status;
    $location = public_path() . '/img/inside.png';
    $email_info = ['user' => Auth::user(),'location' => $location,];
    $email = $request->employee_email;
    $name = $request->employee_name;
    $estado = $request->status_str;
    $manager = $request->manager->nombre;

    if($status == 10){

      if($this->sentSurvey()){
        $direccion = "encuesta-satisfaccion/questions/".$request->id;
      
        Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => $direccion,'request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado){
          $message->to($email,$name)->subject("Tu solicitud ha sido completada satisfactoriamente.");
        });
      
        $email = "karina.ascencionhernandez@profuturo.com.mx";
     
        Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => '','request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado,$request){

          $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
        });


        $email = $request->manager->email;
        

        Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => '','request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado,$request){
          $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
        });        
      }else{
          
          $direccion = "";
          $mensaje = "Tu solicitud ha sido completada satisfactoriamente.";

          Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => $direccion,'request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado){
            $message->to($email,$name)->subject("Tu solicitud ha sido completada satisfactoriamente.");
          });
        
          $email = "karina.ascencionhernandez@profuturo.com.mx";
       
          Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => '','request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado,$request){

            $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
          });


          $email = $request->manager->email;
          
          Mail::send('admin::email_templates.general_request',['estado' => $estado,'direccion' => '','request' => $request,'manager' => $manager],function($message) use ($email,$name,$estado,$request){
            $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
          });        
      }

    }else{
      
      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request,'direccion' => '','manager' => $manager],function($message) use ($email,$name,$estado){
        $message->to($email,$name)->subject("TU SOLICITUD HA CAMBIADO DE ESTATUS ");
      });
    }
      
    if(Input::has('num_orden_people'))
      $request->update(['num_orden_people' => Input::get('num_orden_people')]); 


    if(Input::has('linea_negocio'))
      $request->update(['linea_negocio' => Input::get('linea_negocio')]); 


    $request->save();

    return Redirect::to(action('UserRequestsController@index'))->withSuccess("Se ha actualizado el estado de la solicitud");
  }   



  public function show($id)
  {
    $general_request = GeneralRequest::find($id);
    if($general_request){
      
      if($general_request->delete()){
        return Redirect::action('GeneralRequestsController@index')->withSuccess('Se cancelo la orden exitosamente.');
      }else{
        return Redirect::back()->withErrors('No se pudo cancelar  la orden');
      }
      
    }else{
        return Redirect::back()->withErrors('No se encontro la orden');
    }
  }

  private function sentSurvey()
  {
      $percentage = 40;
      if(rand(1,100) <= $percentage)
        return true;

      return false;  
  }

}
