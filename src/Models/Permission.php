<?php

namespace SimonMarcelLinden\JWT\Models;

use SimonMarcelLinden\JWT\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Permission model class.
 *
 * This class represents a permission entity in the context of role-based access control (RBAC) within the application.
 * It uses traits to include functionalities like UUIDs, soft delete features, and factory methods for testing.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class Permission extends Model {
	use HasFactory, SoftDeletes, Uuids;

	/**
	 * The attributes that are mass assignable.
	 *
	 * These attributes can be filled in bulk and are safe to be input by users.
	 *
	 * @var string[]
	 */
	protected $fillable = ['slug', 'name', 'description'];

	/**
	 * Permission-User relationship.
	 *
	 * This method defines a many-to-many relationship between permissions and users.
	 * It allows associating permissions with multiple users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany Relation with User model.
	 */
	public function users() {
		return $this->belongsToMany(User::class);
	}

	/**
	 * Child permissions relationship.
	 *
	 * This method defines a one-to-many relationship to itself, representing child permissions.
	 * It allows permissions to have hierarchical structures.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation with Permission model.
	 */
	public function children() {
		return $this->hasMany(Permission::class, 'parent_id');
	}

	/**
	 * Parent permission relationship.
	 *
	 * This method defines an inverse one-to-many relationship to itself, representing the parent permission.
	 * It allows permissions to be part of a hierarchical structure.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation with Permission model.
	 */
	public function parent() {
		return $this->belongsTo(Permission::class, 'parent_id');
	}
}
