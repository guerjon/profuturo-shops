<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

use Watson\Validating\ValidatingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait, ValidatingTrait;

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


	public static function boot()
	{
		parent::boot();
		User::deleting(function($user){
			$user->cartProducts()->detach();
			$user->cartFurnitures()->detach();
			$user->orders()->delete();
			$user->bcOrders()->delete();
			$user->generalRequests()->delete();
			// TODO :: Furniture requests delete
			DB::table('general_requests')->where('manager_id', $user->id)->update([
				'manager_id' => NULL
				]);
		});

		User::restored(function($user){
			$user->orders()->withTrashed()->restore();
			$user->bcOrders()->withTrashed()->restore();
			$user->generalRequests()->withTrashed()->restore();
		});
	}

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

	public function cartFurnitures()
	{
		return $this->belongsToMany('Furniture', 'cart_furnitures')->withPivot('quantity');
	}

	public function orders()
	{
		return $this->hasMany('Order');
	}


	public function furnitureOrders()
	{
		return $this->hasMany('FurnitureOrder');
	}

	public function bcOrders()
	{
		return $this->hasMany('BcOrder');
	}

	public function businessCards()
	{
		return $this->hasMany('BusinessCard', 'ccosto', 'ccosto');
	}


	public function generalRequests()
	{
		return $this->hasMany('GeneralRequest');
	}

	function generalRequestsByManager()
	{
  		return $this->hasMany('GeneralRequest', 'manager_id');
	}

	public function assignedRequests()
	{
		return $this->hasMany('GeneralRequest', 'manager_id');
	}

	public function getCartTotalAttribute()
	{
		$total = 0;
		foreach($this->cart_products as $p){
			$total += $p->price * $p->pivot->quantity;
		}
		return $total;
	}

	public function getCartTotalFurnitureAttribute()
	{
		$total = 0;
		foreach($this->cart_furnitures as $p){
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
					action('AdminGeneralRequestsAssignController@getIndex') => 'Asignación de solicitudes generales',
					action('AdminGeneralRequestsController@index') => 'Solicitudes generales',
					action('AdminReportsController@getIndex') => 'Reportes',
					action('AdminFurnituresController@index') => 'Muebles',
					action('AdminFurnitureCategoriesController@index') => 'Categorías de muebles',

				];
			case 'manager':
				return [
					action('UserRequestsController@getIndex') => 'Solicitudes generales',
					action('CalendarEventsController@getIndex') => 'Agenda',
					action('UrgentRequestsController@getIndex') => 'Solicitudes urgentes',
				];
			case 'user_paper':
				return [
					action('ProductsController@index') => 'Productos',
					action('BusinessCardsController@index') => 'Tarjetas de presentación',
					'/carrito' => 'Mi carrito (papelería)',
					action('OrdersController@index') => 'Mis pedidos (papelería)',
					action('BcOrdersController@index') => 'Mis pedidos (tarjetas)',
					action('OrderFurnituresController@index') => 'Mis pedidos (inmuebles)',
					action('FurnituresController@index') => 'Muebles',
					'/carrito-muebles' => 'Mi carrito (muebles)',


				];
			case 'user_requests':
				return [
					action('GeneralRequestsController@index') => 'Solicitudes generales'
				];
		}
	}
}
