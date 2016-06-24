<?php

class AdminSpiderGraphController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{   
		$surveys = SatisfactionSurvey::query();

		if(Input::has('gerencia'))
			$surveys->whereHas('users',function($q){
				$q->where('users.id','=',Input::get('gerencia'));
			}); 

		if(Input::has('solicitud'))
		  	$surveys->where('satisfaction_surveys.id','=',Input::get('solicitud'));

		if(Input::has('encuesta')){
		  	$surveys->whereHas('generalRequest',function($q){
		  		$q->where('general_requests.id','=',Input::get('encuesta'));
		  	});
		}


		if (Input::has('xls')) {
	  		$excel = App::make('excel');


			  foreach ($surveys->get() as $item) {
				
				$itemArray = [];
				$itemArray['NUMERO DE SOLICITUD GENERAL']   = $item->general_request_id;
				$itemArray['ACTITUD DEL CONSULTOR']   = $this->calculateResult($item->question_one,1);
				$itemArray['SEGUIMIENTO DEL CONSULTOR'] = $this->calculateResult($item->question_two,2);
				$itemArray['TIEMPOS RESPUESTA CONSULTOR'] = $this->calculateResult($item->question_three,3);
				$itemArray['CALIDAD DEL PRODUCTO'] = $this->calculateResult($item->question_four,4);
				$itemArray['POR QUE ACTITUD DEL CONSULTOR'] =  $item->explain_1;
				$itemArray['POR QUE SEGUIMIENTO DEL CONSULTOR'] =  $item->explain_2;
				$itemArray['POR QUE TIEMPOS RESPUESTA CONSULTOR'] = $item->explain_3;
				$itemArray['POR QUE CALIDAD DE PRODUCTO'] = $item->explain_4;
				$itemArray['COMENTARIOS'] = $item->comments;
					
		        $itemArray['CONSULTOR'] =  $item->manager ? $item->manager->nombre : 'SIN CONSULTOR';
		        $itemArray['USUARIO_PROYECTOS'] = $item->user ? $item->user->nombre : 'USUARIO PROYECTOS';				
				$result[] = $itemArray;
			  }
			  
			  if($result){
				Excel::create('Reporte_encuesta',function($excel) use($result){
				   $excel->sheet('Hoja_1', function($sheet) use($result) {
					 $sheet->fromArray($result);
				  });
				})->download('xls');
			  }

			  
		}else{
			return View::make('admin::spider_graph.index')
				->withSurveys(
					$surveys->lists('general_requests.id')
				);  
		}
	}

	public function calculateResult($val,$question)
	{
		switch ($question) {
			case 1:
				switch ($val) {
					case 0:
						return 'Muy mala';
					case 3:
						return 'Mala';
					case 6:
						return 'Buena';
					case 10:
						return 'Excelente';
					default:
						break;
				}
				return '';
			case 2:
				switch ($val) {
					case 0:
						return 'Muy malo';
					case 3:
						return 'Malo';
					case 6:
						return 'Bueno';
					case 10:
						return 'Excelente';
					default:
						break;
				}
				return '';
			case 3:
				switch ($val) {
					case 0:
						return 'Muy malos';
					case 3:
						return 'Malos';
					case 6:
						return 'Buenos';
					case 10:
						return 'Excelentes';
					default:
						break;
				}
				return '';
			case 4:
				switch ($val) {
					case 0:
						return 'Totalmente en desacuerdo';
					case 3:
						return 'En desacuerdo';
					case 6:
						return 'De acuerdo';
					case 10:
						return 'Totalmente de acuerdo';
					default:
						break;
				}
				return '';
			default:
				return '';
		}
	}

}
