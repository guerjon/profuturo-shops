<?php

class AdminDashboardController  extends AdminBaseController {
	
	public function stationery()
	{
		return View::make('admin::dashboard/stationery');
	}

}
