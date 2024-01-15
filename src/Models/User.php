<?php

namespace SimonMarcelLinden\JWT\Models;

use SimonMarcelLinden\JWT\Traits\Uuids;
use SimonMarcelLinden\JWT\Http\Resources\UserResource;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

/**
 * User model class.
 *
 * This class represents a user and is a part of the JWT authentication system. It uses several traits
 * to include common functionalities like authentication and authorization capabilities, UUIDs, factory
 * methods for testing, and soft delete features.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract {
	use Authenticatable, Authorizable, HasFactory, SoftDeletes, Uuids;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	/**
	 * The attributes that are mass assignable.
	 *
	 * These attributes can be filled in bulk and are safe to be input by users.
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'firstname', 'lastname', 'email',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * These attributes will not be displayed in the model's array or JSON form.
	 *
	 * @var string[]
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * The "boot" method of the model.
	 *
	 * This method is called when the model is first initialized. It is used to register any model
	 * event listeners or perform other bootstrapping tasks.
	 */
	protected static function boot() {
		parent::boot();
	}

	/**
	 * Converts the user model to an array.
	 *
	 * This method transforms the user model into an array format. It uses a custom user resource to
	 * resolve the user's attributes, which is useful for API responses.
	 *
	 * @return array The user model transformed into an array.
	 */
	public function toArray() {
		// Custom logic to transform the model to an array.
		return (new UserResource($this))->resolve();
	}

	public function permissions() {
		return $this->belongsToMany(Permission::class);
	}
}
