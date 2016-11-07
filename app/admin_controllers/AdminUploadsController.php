<?

class AdminUploadsController extends AdminBaseController{

	public function index()
	{
		$uploads = Upload::query();
		return View::make('admin::uploads/index')->withUploads($uploads->paginate(10));

	}

}