<?

class AdminUploadsController extends AdminBaseController{

	public function paperUploads()
	{
		$uploads = Upload::where('kind','paper_cards')->orderBy('id','desc');
		return View::make('admin::uploads/paper_cards')->withUploads($uploads->paginate(10));
	}

	public function corporationUploads()
	{
		$uploads = Upload::where('kind','corporation_cards');
		return View::make('admin::uploads/corporation_cards')->withUploads($uploads->paginate(10));
	}

	public function downloadOriginal($upload_id)
	{
		$upload = Upload::find($upload_id);	
		return Response::download($upload->file_path,$upload->file_name);
	}

	public function downloadPaperTemplate()
	{
		return Response::download(storage_path().'/plantilla_tarjetas_papeleria.xlsx');
	}

	public function downloadCorporationTemplate()
	{
		return Response::download(storage_path().'/plantilla_tarjetas_corporativo.xlsx');
	}

}