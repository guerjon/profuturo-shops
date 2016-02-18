<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/mockups/{view}', function($view){
	return View::make("mockups.{$view}");
});

Route::get('plantilla',function(){
	return View::make('admin::email_templates.general_request_notice');
});
Route::controller('encuesta-satisfaccion','SatisfactionSurveyController');

Route::get('login', 'AuthController@getLogin');
Route::post('login', 'AuthController@postLogin');
Route::get('admin-login', 'AuthController@getAdminLogin');
Route::post('admin-login', 'AuthController@postAdminLogin');
Route::get('logout', 'AuthController@getLogout');

Route::controller('api', 'ApiController');


Route::group(['before' => 'auth'], function(){

	Route::group(['prefix' => 'admin'], function()
	{	//Route::controller('users','AdminUsersController');
		Route::get('users/import','AdminUsersController@getImport');
		Route::post('users/import','AdminUsersController@postImport');
		Route::resource('users', 'AdminUsersController');
		Route::resource('products', 'AdminProductsController');
		Route::resource('mobiliario','AdminFurnituresController');
		Route::resource('importar-mobiliario','AdminFurnitureImporterController');
		Route::resource('categories', 'AdminCategoriesController');
		Route::resource('categorias-mobiliario', 'AdminFurnitureCategoriesController');
		Route::resource('orders', 'AdminOrdersController', ['only' => ['index', 'show','destroy']]);
		Route::resource('bc-orders', 'AdminBcOrdersController', ['only' => ['index', 'show','destroy']]);
		Route::resource('furnitures-orders', 'AdminFurnituresOrdersController', ['only' => ['index', 'show','destroy']]);
		Route::resource('business-cards', 'AdminBusinessCardsController');
		Route::resource('general-requests', 'AdminGeneralRequestsController');
		Route::resource('agenda', 'AdminCalendarEventsController');
		Route::resource('agenda-evento', 'AdminCalendarEventsController@show');
		Route::controller('api', 'AdminApiController');
		Route::controller('reports', 'AdminReportsController');
		
		Route::controller('general-requests-assign', 'AdminGeneralRequestsAssignController');
		Route::resource('divisionales','AdminDivisionalController');
		Route::resource('subcategorias-muebles','AdminFurnitureSubcategoriesController');
		Route::post('subcategorias-muebles/{subcategory_id}/edit','AdminFurnitureSubcategoriesController');
		Route::resource('address','AdminAddressController');
		Route::get('grafica-arana','AdminSpiderGraphController@getIndex');
		Route::resource('productos-mac','AdminMacProductsController');
	});

	Route::resource('pedidos', 'OrdersController', ['only' => ['index', 'store', 'show', 'update','destroy']]);
	Route::resource('perfil','ProfileController');
	Route::resource('pedidos-mueble', 'OrderFurnituresController', ['only' => ['index', 'store', 'show', 'update','destroy']]);
	Route::post('pedidos-mueble/{order_id}','OrderFurnituresController@postReceive');

	Route::resource('pedidos-tp', 'BcOrdersController');

	Route::post('pedidos/{order_id}', 'OrdersController@postReceive');
	Route::resource('solicitudes-generales', 'GeneralRequestsController');
	
	Route::resource('cargas','LoadsController');
	Route::resource('direcciones','AddressController');

	Route::post('pedidos-tp/{bc_order_id}', 'BcOrdersController@postReceive');
	Route::get('productos/{category}/{subcategory}', 'ProductsController@index');
	Route::get('productos/{category}', 'ProductsController@index');
	Route::get('productos', 'ProductsController@index');

	Route::get('mac-productos', 'MacProductsController@index');
	Route::get('mac-productos/{category}', 'MacProductsController@index');
	Route::resource('pedidos-mac','MacOrdersController');

	Route::get('mobiliario', 'FurnituresController@index');
	Route::get('mobiliario/{category}/{subcategory}', 'FurnituresController@index');
	Route::get('mobiliario/{category}', 'FurnituresController@index');




	Route::get('tarjetas-presentacion', 'BusinessCardsController@index');
	Route::controller('agregar-producto','AddProductsController');
	Route::controller('agregar-mobiliario','AddFurnituresController');
	Route::controller('solicitudes-asignadas', 'UserRequestsController');
	Route::controller('agenda', 'CalendarEventsController');
	Route::controller('solicitudes-urgentes', 'UrgentRequestsController');
	Route::controller('encuesta-satisfacción','SatisfactionSurveyController');
	Route::controller('/', 'HomeController');




});
