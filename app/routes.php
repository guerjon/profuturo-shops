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


Route::get('login', 'AuthController@getLogin');
Route::post('login', 'AuthController@postLogin');
Route::get('admin-login', 'AuthController@getAdminLogin');
Route::post('admin-login', 'AuthController@postAdminLogin');
Route::get('logout', 'AuthController@getLogout');

Route::controller('api', 'ApiController');


Route::group(['before' => 'auth'], function(){

	Route::group(['prefix' => 'admin'], function()
	{
		Route::resource('users', 'AdminUsersController');
		Route::resource('products', 'AdminProductsController');
		Route::resource('categories', 'AdminCategoriesController');
		Route::resource('budget', 'AdminBudgetsController');
		Route::resource('orders', 'AdminOrdersController', ['only' => ['index', 'show','destroy']]);
		Route::resource('bc-orders', 'AdminBcOrdersController', ['only' => ['index', 'show']]);
		Route::resource('business-cards', 'AdminBusinessCardsController');
		Route::resource('general-requests', 'AdminGeneralRequestsController');
		Route::resource('agenda', 'AdminCalendarEventsController');
		Route::controller('api', 'AdminApiController');
		Route::controller('reports', 'AdminReportsController');
		Route::controller('general-requests-manager', 'AdminGeneralRequestsManagerController');
	});

	Route::resource('pedidos', 'OrdersController', ['only' => ['index', 'store', 'show', 'update','destroy']]);
	Route::resource('pedidos-tp', 'BcOrdersController');
	Route::post('pedidos/{order_id}', 'OrdersController@postReceive');
	Route::resource('solicitudes-generales', 'GeneralRequestsController');
	Route::post('pedidos-tp/{bc_order_id}', 'BcOrdersController@postReceive');
	Route::get('productos/{category}/{subcategory}', 'ProductsController@index');
	Route::get('productos/{category}', 'ProductsController@index');
	Route::get('productos', 'ProductsController@index');
	Route::get('tarjetas-presentacion', 'BusinessCardsController@index');
	Route::controller('agregar-producto','AddProductsController');
	Route::controller('/', 'HomeController');
});
