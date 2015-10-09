<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

use Watson\Validating\ValidatingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface,StaplerableInterface{

	use UserTrait, RemindableTrait, SoftDeletingTrait, ValidatingTrait,EloquentTrait;

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
		'role' => 'in:manager,admin,user_requests,user_paper,user_furnitures',
		'num_empleado' =>'unique:users,num_empleado'
	];
	protected $appends = ['acceso'];
	


	protected $validationMessages = [
		'num_empleado.unique' => 'El numero de empleado ya esta en uso'
	];	


  public function __construct($attributes = array()){

    $this->hasAttachedFile('image', [
      'styles' => [
        'medium' => '300x300',
        'thumb' => '100x100',
        'mini' => '50x50'
        ]
      ]);
    parent::__construct($attributes);
  }



	public static function boot()
	{
		parent::boot();
		parent::bootStapler();
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
	public function getIsManagerAttribute()
	{
		return $this->role == 'manager';
	}

	public function getIsUserRequestsAttribute(){
		return $this->role == 'user_requests';	
	}

	public function getUserPaperAttribute()
	{
		return $this->role == 'user_paper';
	}

	public function cartProducts()
	{
		return $this->belongsToMany('Product', 'cart_products')->withPivot('quantity');
	}

	public function cartFurnitures()
	{
		return $this->belongsToMany('Furniture', 'cart_furnitures')->withPivot('quantity','company','assets','ccostos','color','id_active');
	}

	public function orders()
	{
		return $this->hasMany('Order');
	}

	public function region(){
		return $this->belongsTo('Region');
	}

	public function divisional()
	{
		return $this->belongsTo('Divisional');
	}

	public function color(){
		return $this->belongsTo('Color');
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
					action('AdminFurnituresOrdersController@index') => 'Pedidos mobiliario',
					action('AdminCalendarEventsController@index') => 'Agenda',
					action('AdminGeneralRequestsAssignController@getIndex') => 'Asignación de solicitudes generales',
					action('AdminGeneralRequestsController@index') => 'Solicitudes generales',
					action('AdminReportsController@getIndex') => 'Reportes',
					action('AdminFurnituresController@index') => 'Mobiliario',
					action('AdminFurnitureCategoriesController@index') => 'Categorías de mobiliario',
					action('AdminDivisionalController@index') => 'Divisionales',

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
					
					

				]
				;
			case 'user_requests':
				return [
					action('GeneralRequestsController@index') => 'Solicitudes generales',
				];
			case 'user_furnitures':
				return [
					action('FurnituresController@index') => 'Mobiliario',
					'/carrito-muebles' => 'Mi carrito (mobiliario)',
					action('OrderFurnituresController@index') => 'Mis pedidos (mobiliario)',
				];	
		}
	}
}
