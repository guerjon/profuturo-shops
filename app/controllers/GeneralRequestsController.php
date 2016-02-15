<?

class GeneralRequestsController extends BaseController{

  public function index()
  {

    $active_tab = Input::get('active_tab', 'assigned');
  

    $assigneds = ['ASIGNADO','NO ASIGNADO'];
    $active_category = ['ASIGNADO','NO ASIGNADO'];

    $requests = Auth::user()->generalRequests()->orderBy('rating')->get();
    return View::make('general_requests.index')->withRequests($requests)
                                               ->withActiveCategory($active_category)
                                               ->withActiveTab($active_tab);
  }

  public function store()
  {
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
    
    $email = "karina.ascencionhernandez@profuturo.com.mx";
    
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
    $base = app_path();



    if($status == 10){
    
      Mail::send('admin::email_templates.general_request',['estado' => $estado,'base' => $base,'request' => $request],function($message) use ($email,$name,$estado){
        $message->to($email,$name)->subject("Tu solicitud ha sido completada satisfactoriamente.");
      });
    
      $email = "karina.ascencionhernandez@profuturo.com.mx";
    
      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request],function($message) use ($email,$name,$estado,$request){
        $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
      });


      $email = $request->manager->email;

      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request],function($message) use ($email,$name,$estado,$request){
        $message->to($email,$name)->subject("La solicitud ".$request->id." ha sido completada satisfactoriamente. ");
      });

    }else{

      
      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request],function($message) use ($email,$name,$estado){
        $message->to($email,$name)->subject("TU SOLICITUD HA CAMBIADO DE ESTATUS ");
      });


      $email = "karina.ascencionhernandez@profuturo.com.mx";
    
      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request],function($message) use ($email,$name,$estado,$request){
        $message->to($email,$name)->subject("La solicitud ".$request->id." cambio de estatus. ");
      });
 

      $email = $request->manager->email;

      Mail::send('admin::email_templates.general_request',['estado' => $estado,'request' => $request],function($message) use ($email,$name,$estado,$request){
        $message->to($email,$name)->subject("La solicitud ".$request->id." cambio de estatus. ");
      });

    }
    

    $request->save();

  return Redirect::to(action('UserRequestsController@getIndex'))->withSuccess("Se ha actualizado el estado de la solicitud");
  } 

}
