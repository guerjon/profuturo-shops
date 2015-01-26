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

	protected $fillable = ['email', 'first_name', 'last_name', 'password', 'role'];

	protected $rules = [
		'email' => 'required|email|unique:users,email',
		'first_name' => 'required',
		'last_name' => 'required',
		'password' => 'required',
		'role' => 'in:user,admin'
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


	public function getCartTotalAttribute()
	{
		$total = 0;
		foreach($this->cart_products as $p){
			$total += $p->price * $p->pivot->quantity;
		}
		return $total;
	}

}
