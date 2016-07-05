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

	protected $dates = ['deleted_at'];
	
	protected $rules = [
		'gerencia' => 'required',
		'role' => 'in:manager,admin,user_requests,user_paper,user_furnitures,user_loader,user_mac,user_loader,user_corporation,user_training',
		'num_empleado' =>'unique:users,num_empleado'
	];
	


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


    public function messages()
    {
        return $this->belongsToMany('Message');
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

	public function getIsUserPaperAttribute()
	{
		return $this->role == 'user_paper';
	}

	public function getUserMacAttribute()
	{
		return $this->role == 'user_mac';
	}
	
	public function getUserCorporationAttribute()
	{
		return $this->role == 'user_corporation';
	}

	public function getUserTrainingAttribute()
	{
		return $this->role == 'user_training';
	}

	public function cartProducts()
	{
		return $this->belongsToMany('Product', 'cart_products')->withPivot('quantity');
	}

	public function cartFurnitures()
	{
		return $this->belongsToMany('Furniture', 'cart_furnitures')->withPivot('quantity','company','assets','ccostos','id_active','color');
	}

	public function cartMac()
	{
		return $this->belongsToMany('MacProduct', 'cart_mac_products')->withPivot('quantity');
	}

	public function cartCorporation()
	{
		return $this->belongsToMany('CorporationProduct', 'cart_corporation_products')->withPivot('quantity','description');
	}

	public function cartTraining()
	{
		return $this->belongsToMany('TrainingProduct', 'cart_training_products')->withPivot('quantity','description');
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

	public function address(){
		return $this->belongsTo('Address');
	}

	public function furnitureOrders()
	{
		return $this->hasMany('FurnitureOrder');
	}

	public function furnitureRequestsOrders()
	{
		return $this->belongsToMany('FurnitureOrder');
	}
	
	public function macOrders()
	{
		return $this->hasMany('MacOrder');
	}

	public function corporationOrders()
	{
		return $this->hasMany('CorporationOrder');
	}

	public function trainingOrders()
	{
		return $this->hasMany('TrainingOrder');
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

	public function surveys()
	{
		 return $this->hasManyThrough( 'SatisfactionSurvey','GeneralRequest');
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

	public function getCartTotalMacAttribute()
	{
		$total = 0;
		foreach($this->cart_Mac as $p){
			$total += $p->price * $p->pivot->quantity;
		}
		return $total;
	}

	public function getMenuActionsAttribute(){
		switch($this->role){
			case 'admin':
				return [
					action('AdminUsersController@index') => 'Usuarios',
					action('AdminCategoriesGeneralController@getIndex') => 'Categorías',
					action('AdminProductsGeneralController@getIndex') => 'Productos',
					action('AdminOrdersGeneralController@getIndex') => 'Pedidos',
					action('AdminCalendarEventsController@index') => 'Agenda',
					action('AdminGeneralRequestsAssignController@getIndex') => 'Asignación de solicitudes generales',
					action('AdminGeneralRequestsController@index') => 'Solicitudes generales',
					action('AdminReportsController@getIndex') => 'Reportes',
					action('AdminDivisionalController@index',['active_tab' => '1']) => 'Divisionales',
					action('AdminSpiderGraphController@getIndex') => 'Estadisticas de encuestas',
					action('AdminDashboardController@stationery') => 'Dashboard Papeleria'
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
					action('OrdersController@index') => 'Mis pedidos (papelería)',
					action('BcOrdersController@index') => 'Mis pedidos (tarjetas)',
				];
			case 'user_requests':
				return [
					action('GeneralRequestsController@index') => 'Solicitudes generales',
				];
			case 'user_loader':
				return [
					action('AddressController@index') => 'Direcciones',
					action('LoadsController@index') => 'Cargas',
				];	
			case 'user_furnitures':
				return [
					action('FurnituresController@index') => 'Mobiliario',
					'/carrito-muebles' => 'Mi carrito (mobiliario)',
					'/pedidos-mueble' => 'Mis pedidos',
					action('FurnitureRequestsController@index') => 'Solicitudes sistema'
				];

			case 'user_mac':
				return [
					action('MacProductsController@index') => 'Productos',
					action('MacOrdersController@index') => 'Mis pedidos',
				];
			case 'user_corporation':
				return [
					action('CorporationProductsController@index') => 'Productos',
					action('CorporationOrdersController@index') => 'Mis Pedidos',
				];	
			case 'user_training':
				return [
					action('TrainingProductsController@index') => 'Productos',
					action('TrainingOrdersController@index') => 'Mis Pedidos',

				];
		}
	}
}
