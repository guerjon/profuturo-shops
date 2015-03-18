<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

use Watson\Validating\ValidatingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, ValidatingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $guarded = ['remember_token', 'created_at', 'updated_at'];

	protected $rules = [
		'gerencia' => 'required',
		'password' => 'required',
		
		'role' => 'in:manager,admin,user_requests,user_paper'
	];

	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}


	public function getIsAdminAttribute()
	{
		return $this->role == 'admin';
	}

	public function cartProducts()
	{
		return $this->belongsToMany('Product', 'cart_products')->withPivot('quantity');
	}

	public function orders()
	{
		return $this->hasMany('Order');
	}

	public function bcOrders()
	{
		return $this->hasMany('BcOrder');
	}

	public function bcOrderExtras()
	{
		return $this->hasMany('BcOrdersExtras');
	}

	public function businessCards()
	{
		return $this->hasMany('BusinessCard', 'ccosto', 'ccosto');
	}

	public function generalRequests()
	{
		return $this->hasMany('GeneralRequest');
	}

	public function getCartTotalAttribute()
	{
		$total = 0;
		foreach($this->cart_products as $p){
			$total += $p->price * $p->pivot->quantity;
		}
		return $total;
	}



	public function getMenuActionsAttribute(){
		switch($this->role){
			case 'admin':
				return [
					action('AdminUsersController@index') => 'Usuarios',
					action('AdminCategoriesController@index') => 'Categorías',
					action('AdminProductsController@index') => 'Productos',
					action('AdminBusinessCardsController@index') => 'Tarjetas de presentación',
					action('AdminOrdersController@index') => 'Pedidos papelería',
					action('AdminBcOrdersController@index') => 'Pedidos tarjetas',
					action('AdminCalendarEventsController@index') => 'Agenda',
					action('AdminGeneralRequestsController@index') => 'Solicitudes generales',
					action('AdminReportsController@getOrdersReport') => 'Reporte de pedidos papelería',
					action('AdminReportsController@getBcOrdersReport') => 'Reporte de pedidos de tarjetas de presentación',
				];
			case 'manager':
				return [
					action('ProductsController@index') => 'Productos',
					action('BusinessCardsController@index') => 'Tarjetas de presentación',
					'/carrito' => 'Mi carrito (papelería)',
					action('OrdersController@index') => 'Mis pedidos (papelería)',
					action('BcOrdersController@index') => 'Mis pedidos (tarjetas)',

				];
			case 'user_paper':
				return [
					action('ProductsController@index') => 'Productos',
					action('BusinessCardsController@index') => 'Tarjetas de presentación',
					'/carrito' => 'Mi carrito (papelería)',
					action('OrdersController@index') => 'Mis pedidos (papelería)',
					action('BcOrdersController@index') => 'Mis pedidos (tarjetas)',

				];
			case 'user_requests':
				return [
					action('GeneralRequestsController@index') => 'Solicitudes generales'
				];
		}
	}
}
